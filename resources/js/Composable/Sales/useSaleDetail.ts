import { useToast } from 'primevue';
import { inject } from 'vue';
import { saleKey } from '@/Injections/saleKeys';

const useSaleDetail = () => {
  const form = inject(saleKey)!;
  const toast = useToast();
};
