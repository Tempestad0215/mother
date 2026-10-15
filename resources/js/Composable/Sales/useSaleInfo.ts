import { AutoCompleteCompleteEvent, AutoCompleteOptionSelectEvent, useToast } from 'primevue';
import axios from 'axios';
import { clientBaseI, ClientRncI } from '@/Interfaces/ClientInterface';
import { computed, inject, ref } from 'vue';
import { saleKey } from '@/Injections/saleKeys';
import { urlRNC } from '@/Global/Helpers';
import { SaleTypeEnum } from '@/Helpers/SaleHelper';
import { useSaleSequence } from '@/Composable/Sales/useSaleSequence';

export function useSaleInfo(onNeedRnc?: () => void) {
  const form = inject(saleKey)!;
  const toast = useToast();

  const { getSequence } = useSaleSequence(form);

  const clientFiltered = ref<clientBaseI[]>([]);

  const hasRnc = computed(() => {
    return form.invoice_type.toUpperCase() !== 'B02';
  });

  const isRefund = computed((): boolean => {
    return form.type === SaleTypeEnum.DEVOLUCION;
  });

  const clientName = computed({
    get: () => form.client_name,
    set: (v) => (form.client_name = v),
  });

  const getClient = async (data: AutoCompleteOptionSelectEvent): Promise<void> => {
    const item = data.value as clientBaseI;

    form.client_name = item.name;
    form.client_uuid = item.uuid;
    form.client_rnc = item.type_rnc;

    if (form.invoice_type !== 'B02') {
      form.client_rnc = item.personal_id || '';
      onNeedRnc?.();
      return;
    }

    await getSequence(item.type_rnc);
  };

  const searchClient = async (event: AutoCompleteCompleteEvent): Promise<void> => {
    const value = event.query as string;
    if (value.length <= 0) return;

    try {
      const res = await axios.get(route('client.get.json', { search: value }));

      if (res.data && Array.isArray(res.data) && res.data.length > 0) {
        clientFiltered.value = res.data as clientBaseI[];
      } else {
        clientName.value = value;
        return;
      }

      // clientFiltered.value = response.data as Array<clientBaseI>;
    } catch (err) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Error al obtener los datos del cliente',
        life: 3000,
      });
    }
  };

  const getClientRnc = async (): Promise<void> => {
    if (form.client_rnc.length < 7) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'El RNC debe tener al menos 7 caracteres',
        life: 3000,
      });
    } else {
      try {
        const dataClean = form.client_rnc.trim();
        const result = await axios.get(`${urlRNC}${dataClean}`);
        const data = result.data as ClientRncI;

        if (data.status === 'SUSPENDIDO') {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Este Contribuyente Esta Suspendido, Por Favor Elegir Otro',
            life: 3000,
          });
        } else {
          form.client_name = data.razon_social;
          form.client_social = data.razon_social;
          form.client_rnc_status = data.status;

          toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: 'RNC Cargado Correctamente',
            life: 3000,
          });
        }
      } catch (err) {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'Error al obtener el RNC',
          life: 3000,
        });
      }
    }
  };

  return {
    searchClient,
    getClientRnc,
    getClient,
    clientName,
    hasRnc,
    isRefund,
    clientFiltered,
  };
}
