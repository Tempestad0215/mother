// Interface for CreditNoteBalance
export interface CreditNoteBalance {
  code: string;
  n_available: string;
  n_available_new: number;
  ncf: string | null;
  uuid: string;
  created_at: string;
  dayRemaining: number;
  expireSoon: boolean;
}
