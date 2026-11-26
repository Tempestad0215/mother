
import { inject } from 'vue';
import { saleKey } from '@/Injections/saleKeys';

const useSaleDetail = () => {
  const form = inject(saleKey)!;
};
