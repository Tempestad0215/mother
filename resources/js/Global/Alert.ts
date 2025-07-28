import Swal from "sweetalert2";
import axios from "axios";


export const successHttp = (msj: string) => {
	void Swal.fire({
		position: "center",
		icon: "success",
		title: "Exito",
		text: msj,
		showConfirmButton: false,
		timer: 1500
	});
}

export const errorHttp = (msj: string) => {
	void Swal.fire({
		position: "center",
		icon: "error",
		title: "Error",
		text: msj
	});
}

export const loadingAlert = (title: string, text: string): HTMLElement | null => {
	let popupEle: HTMLElement | null = null;
	
	void Swal.fire({
		title,
		text,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		},
		willOpen(popup: HTMLElement) {
			popupEle = popup;
		}
	});
	
	// Retorna el popup inmediatamente (Swal.fire sigue abierto)
	return popupEle;
};


export const showPDf = async (title:string,path:string) => {
	
	loadingAlert('Cargando...', 'Creando el PDF para la etiqueta')
	
	const response = await axios.get(path, {
		responseType: 'blob' // ¡Importante para manejar PDF!
	});
	
	// Crear URL temporal para el blob del PDF
	const pdfBlob = new Blob([response.data], {type: 'application/pdf'});
	const pdfUrl = URL.createObjectURL(pdfBlob);
	
	// Mostrar SweetAlert con iframe
	await Swal.fire({
		title: 'Vista previa de la etiqueta',
		html: `
        <iframe
          src="${pdfUrl}"
          width="100%"
          height="600px"
          style="border: none;"
        ></iframe>
      `,
		allowOutsideClick: false,
		showCancelButton: true,
		showConfirmButton: false,
		cancelButtonText: 'Cerrar',
		width: '80%',
		padding: '2em',
		didOpen: () => {
			// Limpiar la URL cuando se cierre el modal
			Swal.getPopup()?.addEventListener('hidden.bs.modal', () => {
				URL.revokeObjectURL(pdfUrl);
			});
		}
	})
}