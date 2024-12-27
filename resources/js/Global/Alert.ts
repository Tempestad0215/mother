import Swal from "sweetalert2";


export const successHttp = (msj:string) => {
    Swal.fire({
        position: "center",
        icon: "success",
        title: "Exito",
        text: msj,
        showConfirmButton: false,
        timer: 1500
      });
}

export const errorHttp = (msj:string) => {
    Swal.fire({
        position: "center",
        icon: "error",
        title: "Error",
        text: msj
      });
}
