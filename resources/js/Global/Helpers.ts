import Swal from "sweetalert2";
import axios from "axios";


/*
* Variables compartida general
 */
export const moneyConfig =  {
    decimal: '.',
    thousands: ',',
    prefix: '',
    suffix: '',
    precision: 2,
    masked: false,
    allowBlank: false
}

export const configPercent =  {
    prefix: '',
    suffix: '%',
    min: 0,
    max: 100,
    precision: 2,
    masked: false,
    allowBlank: false
}

/**
 * PAra mostar los numeros enteros
 */
// export const moneyConfigInt =  {
//     decimal: '.',
//     thousands: ',',
//     prefix: '',
//     suffix: '',
//     precision: 0,
//     masked: false
// }

/**
 * Esto es para porcentaje
 */
// export const moneyConfigPer =  {
//     decimal: '.',
//     thousands: ',',
//     prefix: '',
//     suffix: '%',
//     precision: 0,
//     masked: false,
//     allowBlank: false
// }





/**
 * Limpiar y convertir a float
 * @param val
 */
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


/**
 * Convertir a dinero
 * @param value
 */
export const getMoney = (value:number = 0) => {
    return new Intl.NumberFormat('es-DO',{
        style: 'currency',
        currency: 'DOP',
    }).format(value);
}




/**
 * convertir a dinero sin prefijo
 * @param value
 */
export const formatNumberPlane = (value:number):string => {
    // Limitar a dos decimales
    const roundedAmount = Math.round(value * 100) / 100;

    // Convertir a formato de moneda
    return roundedAmount.toLocaleString('es-DO', {
        // Cambia 'DOP' por la moneda que necesites
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}




/**
 * convertir los datos a centimo
 * @param value
 */
export const getPenny = (value:number) => {
    return value * 100;
}

/**
 * Convertir a pesos normales
 * @param value
 */
export const getCoin = (value:number) => {
    return value / 100;
}



/*
 * Buscar el RNC de los datos
 */
export const getRncHelper = async (data: string):Promise<string> => {

    //Preguntar para buscar los datos
    const result = await Swal.fire({
        title: "Desea Buscar Contribuyente?",
        text: "Por favor, elija la Opcion!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Buscar!",
        cancelButtonText: "Cancelar"
    });

    //Verificar
    if (result.isConfirmed){
        try {
            const response = await axios.get(route('sequence.getRnc', { rnc: data }));
            const status = response.data.status;

            if (status === "SUSPENDIDO") {
                return "SUSPENDIDO";
            } else {
                return JSON.stringify(response.data);
            }
        } catch (error) {
            return "ERROR";
        }
    } else {
        return "CANCELLED";
    }

}


/**
 * Tipos de secuencia ncf
 */
export const getSequenceType = (type:string):string =>
{
    switch (type){
        case "B01":
            return "Factura Credito Fiscal";
        case "B02":
            return "Factura Consumidor Final";
        case "B03":
            return "Nota Debito";
        case "B04":
            return "Nota Credito";
        case "B11":
            return "Comprobante de Compra";
        case "B12":
            return "Registro Unido de Ingresos";
        case "B13":
            return "Comprobante de Gasto Menor";
        case "B14":
            return "Factura Regimen Especial";
        case "B15":
            return "Factura Gubernamental";
        case "B16":
            return "Factura Para Exportacion";
        case "B17":
            return "Comprobante de Pago al Exterior";
        default:
            return "No Existe";
    }
}


/**
 * Generar colores para los charts
 * @param numItems
 */
export const generateColors = (numItems:number) => {
    const colors = [];

    // Hue (tono) variará entre 0 y 360 para cubrir el espectro de colores.
    // Saturación y luminosidad se mantienen constantes para armonía.
    for (let i = 0; i < numItems; i++) {
        const hue = Math.floor((i / numItems) * 360); // Distribuye los colores de manera armónica.
        const saturation = 70; // Saturación constante para mantener la viveza.
        const lightness = 50; // Luminosidad constante para colores equilibrados.

        colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`); // Color armónico
    }

    return colors;
};


/**
 *Colocar la hora en la busqueda
 * @param h
 * @param m
 * @param s
 * @param ms
 */
export const setHour = (h:number, m:number, s:number, ms:number):string => {
    //Tomar la fecha del dia
    const now = new Date();

    //Fecha de inicio
    const date = new Date(now);
    //colocar la hora
    date.setHours(h,m,s,ms);

    //Formatear la fecha
    //Obtener el input para poner la fecha
    return  getDateInUtc4(date);
}



/**
 * Convertir los datos
 * @param date
 */
const getDateInUtc4 = (date:Date):string => {
    date.setHours(date.getHours() - 4);

    //Convertir
    const isoString = date.toISOString();
    //Devolver los datos
    return isoString.slice(0,16);
}


/**
 * Para unir la paginacion con la busqueda y otros datos
 * @param url
 * @param search
 * @param perPage
 */
export const paginationJoin = (url:string, search:string, perPage:number) => {
    return url+'&search='+search+'&perPage='+perPage;
}



export const printPdf = (uuid: string) => {
    const popupOptions = `
        width=800,
        height=600,
        top=${(screen.height - 600) / 2},
        left=${(screen.width - 800) / 2},
        resizable=no,
        scrollbars=no,
        status=no
    `;

    // Abrir la ventana emergente
    const popupWindow = window.open(route('invoice.getA',{sale: uuid}), '_blank', popupOptions);

    // Verificar que la ventana se haya abierto
    if (!popupWindow || popupWindow.closed || typeof popupWindow.closed === 'undefined') {
        alert('Permite las ventanas emergentes en tu navegador.');
        return;
    }

    // Esperar a que la ventana se cargue y luego iniciar la impresión
    popupWindow.onload = () => {
        popupWindow.print(); // Llamar la función de imprimir
    };
}





