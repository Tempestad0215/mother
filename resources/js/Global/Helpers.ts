
export const moneyConfig = {
    decimal: '.',
    thousands: ',',
    prefix: '',
    suffix: '',
    precision: 2,
    masked: false
}

export const formatNumber = (val:string | number):number  =>
{
    // let value:number = 0;
    if(typeof val === 'string')
    {
        // Convertir el valor a flotante
        let limpio:string = val.replace(/[^\d.]/g,'');
        val = parseFloat(limpio);

    }

    // Devolver los datos tal cual
    return val;

}

export const getMoney = (value:number) => {
    return new Intl.NumberFormat('es-DO',{
        style: 'currency',
        currency: 'DOP',
    }).format(value);
}


export const readPDF = (value:string) => {

    //Decodificar la cadena
    const binaryString = atob(value);
    const len = binaryString.length;
    const bytes = new Uint8Array(len);

    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }

    //Crear el b los a partir del array de byte
    const blob = new Blob([bytes], {type: 'application/pdf'});

    //Crear la Url para abrir
    const url = URL.createObjectURL(blob);

    const newTab = window.open(url, '_blank');

    if(newTab)
    {
        newTab.focus();
    }else{
        console.error('No se pudo abrir la nueva pestaña. Asegúrate de que el navegador no esté bloqueando ventanas emergentes.');
    }
}
