import axios from "axios";
import {useRoute} from "ziggy-js";

/**
 *
 * Configuracionde dinero
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

/**
 * configuracion de porcentaje
 */
export const configPercent =  {
    prefix: '',
    suffix: '%',
    min: 0,
    max: 100,
    precision: 2,
    masked: false,
    allowBlank: false
}

// /**
//  * Para crear los meses del año
//  */
export const month = [
    { name: 'Enero', day: 31 },
    { name: 'Febrero', day: 28 },
    { name: 'Marzo', day: 31 },
    { name: 'Abril', day: 30 },
    { name: 'Mayo', day: 31 },
    { name: 'Junio', day: 30 },
    { name: 'Julio', day: 31 },
    { name: 'Agosto', day: 31 },
    { name: 'Septiembre', day: 30 },
    { name: 'Octubre', day: 31 },
    { name: 'Noviembre', day: 30 },
    { name: 'Diciembre', day: 31 }
];

/**
 *
 */
export const getYear = ():number[] => {
    //Para guartdar los datos
    const years:number[] = [];

    //Crear el listado de a;os
    for (let i = 2015; i <= new Date().getFullYear(); i++) {
        years.push(i);
    }
    //Retornar los years
    return years;
}

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
        style: 'decimal',
        currency: 'DOP',
    }).format(value);
}

/**
 * Busca el RNC y devuelve un string
 * @param data
 */
// export const getRncHelper = async (data: string):Promise<"SUSPENDIDO" | "ERROR" | "CANCELLED" | rncClientI> => {
//
//     //Preguntar para buscar los datos
//     const result = await Swal.fire({
//         title: "Desea Buscar Contribuyente?",
//         text: "Por favor, elija la Opcion!",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Si, Buscar!",
//         cancelButtonText: "Cancelar"
//     });
//
//     //Verificar
//     if (result.isConfirmed){
//         let info:string = data.replace(/-/g, "");
//
//         try {
//             const response = await axios.get(route('sequence.getRnc', { rnc: info }));
//             const status = response.data.status;
//
//             if (status === "SUSPENDIDO") {
//                 return "SUSPENDIDO";
//             } else {
//                 return response.data;
//             }
//         } catch (error) {
//             return "ERROR";
//         }
//     } else {
//         return "CANCELLED";
//     }
//
// }


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
 *
 * @param url
 * @param field
 * @param search
 * @param perPage
 */
export const paginationJoin = (url:string,field: string, search:string, perPage:number):string => {
    return url+'&field'+field+'&search='+search+'&perPage='+perPage;
}

/**
 * Para imprimir los pdf
 * @param urlName
 */
export const printPdf = (urlName: string) => {
    const width:number = 800; // Ancho predeterminado
    const height:number = 600; // Altura predeterminada

    // Calcular la posición para centrar la ventana
    const screenWidth:number = window.screen.width;
    const screenHeight:number = window.screen.height;
    const left:number = (screenWidth - width) / 2;
    const top:number = (screenHeight - height) / 2;

    // Crear características de la ventana emergente
    const popupFeatures = `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`;

    //crea la ventana de impresion
    const printWindow = window.open(urlName,'_blank', popupFeatures);

    //Error de la cosas
    if (!printWindow) {
        // errorHttp('No se Puede Abrir La Ventana');
        return;
    }

    // Al momento de cargar la ventana
    printWindow.onload = () => {

        //Imprimir la ventana
        printWindow.print();
    }
}


// Para la exportanciones de excel
export const exportExcel = async (path: string, fielName: string) => {

    try {
        const response = await axios.get(path,{
            responseType: 'blob', // Importante para archivos binario
        });

        //     Crear el enlace  de descarga
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", fielName);
        document.body.appendChild(link);
        link.click();
        link.remove();

        //     Liberar la memoria
        window.URL.revokeObjectURL(url);
    }catch(error) {
        // errorHttp("Error al intentar descargar el docuemento")
    }
}
