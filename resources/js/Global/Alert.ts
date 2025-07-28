import Swal from "sweetalert2";


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
