import { watch } from 'vue';
import { printPdf } from '@/Global/Helpers';
import { InertiaForm, usePage } from '@inertiajs/vue3';
import { CreditNoteBalance } from '@/Interfaces/CreditNoteInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import axios from 'axios';
import { CreateSaleI } from '@/Interfaces/SaleInterface';
import { useNotify } from '@/Composable/Shared/useNotify';

export interface UseSaleCreditNoteOptions {
  onSuccess?: () => void;
}

export function useSaleCreditNote(
  form: InertiaForm<CreateSaleI>,
  options: UseSaleCreditNoteOptions = {}
) {
  const notify = useNotify();
  const page = usePage();

  const createCreditNotes = () => {
    form.post(route('credit-note.store', { sale: form.uuid }), {
      onSuccess: () => {
        notify.success('Nota de Credito Creada Correctamente');
        printPdf(route('invoice.credit-note', { creditNote: page.flash.credit_uuid }));
        form.reset();
        options.onSuccess?.();
      },
      onError: (err) => {
        const errors = Object.values(err);
        notify.error(errors[0]);
      },
    });
  };

  const getInfoCreditNote = (data: CreditNoteBalance): void => {
    const exist = form.credit_notes.find((item) => item.uuid === data.uuid);

    if (exist) {
      notify.error('Esta Nota de Credito Fue Registrada');
      return;
    }

    if (form.credit_notes_amount >= form.amount) {
      notify.warn('El monto de la nota de crédito supera el total de la venta.');
      return;
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
      notify.error('No se encontro la nota de credito');
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
    //Realizar el cálculo
    amountCreditNote();
  };

  const setupCreditNotesWatch = () => {
    watch(
      () => form.credit_notes,
      (_) => {
        form.credit_notes_amount = 0;

        form.credit_notes.forEach((item) => {
          form.credit_notes_amount = parseFloat(
            PreciseCalculator.add(form.credit_notes_amount, item.n_available || 0).toString()
          );
        });

        const result = parseFloat(
          PreciseCalculator.subtract(form.pending, form.credit_notes_amount).toString()
        );

        if (result <= 0.0) {
          form.pending = 0;
          form.returned = 0;
        } else {
          form.pending = result;
        }
      },
      {
        deep: true,
      }
    );
  };

  return {
    createCreditNotes,
    getInfoCreditNote,
    getCreditNoteInfo,
    amountCreditNote,
    deleteCreditNote,
    setupCreditNotesWatch,
  };
}
