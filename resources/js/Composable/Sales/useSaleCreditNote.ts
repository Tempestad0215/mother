import { inject } from 'vue';
import { saleKey } from '@/Injections/saleKeys';
import { useToast } from 'primevue';
import { printPdf } from '@/Global/Helpers';
import { usePage } from '@inertiajs/vue3';
import { CreditNoteBalance } from '@/Interfaces/CreditNoteInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import axios from 'axios';

export interface UseSaleCreditNoteOptions {
  onSuccess?: () => void;
}

export function useSaleCreditNote(options: UseSaleCreditNoteOptions = {}) {
  const toast = useToast();
  const page = usePage();

  const form = inject(saleKey)!;

  const createCreditNotes = () => {
    form.post(route('credit-note.store', { sale: form.uuid }), {
      onSuccess: () => {
        toast.add({
          summary: 'Registro Creado',
          detail: 'Nota de crédito creada correctamente.',
          severity: 'success',
          life: 3000,
        });
        printPdf(route('invoice.credit-note', { creditNote: page.flash.credit_uuid }));
        form.reset();
        options.onSuccess?.();
      },
      onError: (err) => {
        const errors = Object.values(err);
        toast.add({
          summary: 'Error',
          detail: errors[0],
          life: 3500,
          severity: 'error',
        });
      },
    });
  };

  const getInfoCreditNote = (data: CreditNoteBalance): void => {
    const exist = form.credit_notes.find((item) => item.uuid === data.uuid);

    if (exist)
      return toast.add({
        summary: 'Error',
        detail: 'Esta nota de crédito ya fue registrada.',
        severity: 'error',
        life: 3500,
      });

    if (form.credit_notes_amount >= form.amount) {
      return toast.add({
        summary: 'Advertencia',
        detail: 'El monto de la nota de crédito supera el total de la venta.',
        severity: 'warn',
        life: 3500,
      });
    }

    const availableNew = parseFloat(
      PreciseCalculator.subtract(data.n_available, form.amount).toString()
    );

    form.credit_notes.push({
      dayRemaining: data.dayRemaining,
      expireSoon: data.expireSoon,
      uuid: data.uuid,
      code: data.code,
      n_available: parseFloat(data.n_available.toString()).toFixed(2),
      n_available_new: availableNew,
      ncf: data.ncf,
      created_at: data.created_at,
    });
  };

  const getCreditNoteInfo = async (code: string) => {
    try {
      const res = await axios.get(route('credit-note.get', { code: code }));
      // Tranformar los datos en un objeto
      const data = res.data as CreditNoteBalance;
      // Emitir el evento con los datos

      console.log(data);
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'No se encontro la nota de credito1124',
        life: 5000,
      });
    }
  };

  const amountCreditNote = () => {
    //REalizar el cálculo de notas de credito
    form.credit_notes_amount = form.credit_notes.reduce(
      (acc, cur) => acc + Number(cur.n_available),
      0
    );

    //Datos pendientes por pagar
    form.returned = form.credit_notes_amount - form.amount;
    form.pending =
      form.credit_notes_amount - form.amount < 0 ? form.credit_notes_amount - form.amount : 0;
  };

  const deleteCreditNote = (index: number) => {
    //Eliminar solo el dato seleccionado
    form.credit_notes.splice(index, 1);
    //Realizar el calculo
    amountCreditNote();
  };

  return {
    createCreditNotes,
    getInfoCreditNote,
    getCreditNoteInfo,
    amountCreditNote,
    deleteCreditNote,
  };
}
