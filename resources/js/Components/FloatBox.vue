<script setup>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faClose} from "@fortawesome/free-solid-svg-icons";


const show = defineModel('show',{
	default: false,
});
</script>

<template>
	<Transition name="modal">
		<div v-if="show" class="fixed z-[9998] top-0 left-0 w-screen h-screen bg-black backdrop-blur-md bg-opacity-50 flex duration-300">
			<FontAwesomeIcon
				@click="show = false"
				class="text-3xl py-1 absolute right-5 top-5 bg-cyan-100 px-3 rounded-md text-red-500" :icon="faClose"/>
			<div class="modal-container">
				<div class="text-center text-3xl font-bold font-mono">
					<slot name="header"/>
				</div>
				
				<div class="modal-body">
					<slot name="body"/>
				</div>
				
				<div class="modal-footer">
					<slot name="footer"/>
				</div>
			</div>
		</div>
	</Transition>
</template>

<style>


.modal-container {
	width: 90%;
	margin: auto;
	padding: 20px 30px;
	background-color: #fff;
	border-radius: 1rem;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
	transition: all 0.3s ease;
}

.modal-header h3 {
	margin-top: 0;
	color: #42b983;
}

.modal-body {
	margin: 20px 0;
}



.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
	-webkit-transform: scale(1.1);
	transform: scale(1.1);
}
</style>