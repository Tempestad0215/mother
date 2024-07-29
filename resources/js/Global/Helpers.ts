
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
