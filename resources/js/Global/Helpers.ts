import Swal from "sweetalert2";
import axios from "axios";


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


/**
 *
 * @param value
 */
// export const readPDF = (value:string) => {
//
//     //Decodificar la cadena
//     const binaryString = atob(value);
//     const len = binaryString.length;
//     const bytes = new Uint8Array(len);
//
//     for (let i = 0; i < len; i++) {
//         bytes[i] = binaryString.charCodeAt(i);
//     }
//
//     //Crear el b los a partir del array de byte
//     const blob = new Blob([bytes], {type: 'application/pdf'});
//
//     //Crear la Url para abrir
//     const url = URL.createObjectURL(blob);
//
//     const newTab = window.open(url, '_blank');
//
//     if(newTab)
//     {
//         newTab.focus();
//     }else{
//         console.error('No se pudo abrir la nueva pestaña. Asegúrate de que el navegador ' +
//             'no esté bloqueando ventanas emergentes.');
//     }
// }


/**
 * Obtener los RNC
 */
/**
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


