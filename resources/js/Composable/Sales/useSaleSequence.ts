import axios from 'axios';
import { SelectChangeEvent } from 'primevue';
import { InertiaForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { sequenceDataI } from '@/Interfaces/SettingInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { createSequence } from '@/Global/Helpers';
import { CreateSaleI } from '@/Interfaces/SaleInterface';
import { useNotify } from '@/Composable/Shared/useNotify';

export function useSaleSequence(form: InertiaForm<CreateSaleI>) {
  const page = usePage();

  const notify = useNotify();

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
          notify.error('Error al obtener la secuencia');
        }
      }
    } catch (err) {
      form.ncf = '';
      notify.error('Error al obtener la secuencia');
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
        notify.warn(
          `El número de factura se encuentra a ${restante} de los ${data.advise} disponibles.`
        );
      }

      form.ncf = createSequence(data.type, data.next);
    } catch (err) {
      notify.error('No fue posible obtener el siguiente número de factura.');
    } finally {
      loadingNextSequence.value = false;
    }
  };

  return { getSequence, sequenceData, loadingNextSequence, getNextSequence };
}
