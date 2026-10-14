import axios from 'axios';
import { SelectChangeEvent, useToast } from 'primevue';
import { usePage } from '@inertiajs/vue3';
import { inject, ref } from 'vue';
import { sequenceDataI } from '@/Interfaces/SettingInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { createSequence } from '@/Global/Helpers';
import { saleKey } from '@/Injections/saleKeys';

export function useSaleSequence() {
  const toast = useToast();
  const page = usePage();

  const form = inject(saleKey)!;

  const loadingNextSequence = ref<boolean>(false);

  const sequenceData = ref<sequenceDataI | null>(null);

  const getSequence = async (type: string): Promise<void> => {
    try {
      if (page.props.setting.sequence) {
        const result = await axios.get(route('sequence.get', { type: type }));

        if (result.status === 200 && typeof result.data === 'object') {
          sequenceData.value = result.data || null;

          form.sequence_type = type;

          if (sequenceData.value && sequenceData.value.type && sequenceData.value.next != null) {
            form.clearErrors('ncf');
            form.ncf = createSequence(sequenceData.value.type, sequenceData.value.next);
          }
        } else {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Error al obtener la secuencia',
            life: 3000,
          });
        }
      }
    } catch (err) {
      form.ncf = '';
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Error al obtener la secuencia',
        life: 3000,
      });
    }
  };

  const getNextSequence = async (event: SelectChangeEvent) => {
    loadingNextSequence.value = true;
    const info = event.value as string;
    try {
      const res = await axios.get(route('sequence.get', { type: info }));
      const data = res.data as sequenceDataI;
      const restante = parseFloat(PreciseCalculator.subtract(data.to, data.next).toString());

      if (restante <= data.advise) {
        toast.add({
          severity: 'warn',
          summary: 'Advertencia',
          detail: `El número de factura se encuentra a ${restante} de los ${data.advise} disponibles.`,
          life: 3000,
        });
      }

      form.ncf = createSequence(data.type, data.next);
    } catch (err) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'No fue posible obtener el siguiente número de factura.',
        life: 3000,
      });
    } finally {
      loadingNextSequence.value = false;
    }
  };

  return { getSequence, sequenceData, loadingNextSequence, getNextSequence };
}
