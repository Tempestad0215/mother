import { useToast } from 'primevue/usetoast';

//Para utilizar el toast
const toast = useToast();


export const toastMsj = (severity:string, summary:string , msj: string) => {
    //Mensaje de error
    toast.add({
        severity: severity,
        summary: summary,
        detail: msj,
        life: 3000
    });
}
