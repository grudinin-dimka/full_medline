<template>
	<div class="container-inputs">
		<slot></slot>
	</div>
</template>

<script></script>

<style>
.container-inputs {
	display: grid;
	gap: 10px;
}

/* Кастомизированный скролбар. */
textarea::-webkit-scrollbar {
	width: 18px;
}

textarea::-webkit-scrollbar-track {
	background-color: #ffffff;
	border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #ffffff;
	border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb:hover {
	background-color: rgb(210, 210, 210);
	cursor: all-scroll;
}

span.error {
	color: var(--span-color-error);
}

span.create {
	color: var(--span-color-create);
}
</style>
