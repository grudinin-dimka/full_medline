<template>
	<div class="filter">
		<div
			class="filter-title"
			@click="$emit('changeFilterStatus')"
			:class="{ active: filter.status }"
		>
			<div class="name">
				<slot name="title"></slot>
			</div>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="24px"
				viewBox="0 -960 960 960"
				width="24px"
				fill="black"
			>
				<path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
			</svg>
		</div>
		<div class="filter-body" :class="{ active: filter.status }">
			<div class="container-input">
				<input type="text" placeholder="Введите название" />
			</div>
			<ol>
				<li v-for="item in list">{{ item?.name }}</li>
			</ol>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		filter: {
			type: Object,
			required: true,
			default: () => {},
		},
		list: {
			type: Array,
			required: true,
			default: [],
		},
	},
};
</script>

<style>
.filter {
	position: relative;
}

.filter-body {
	position: absolute;
	top: 60px;
	min-width: 160px;
	box-shadow: 0px 5px 15px 5px rgba(0, 0, 0, 0.1);
	z-index: 1;

	padding: 10px;
	border-radius: 10px;
}

.filter > .filter-title {
	cursor: pointer;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	padding: 10px;
	border-radius: 10px;

	font-size: 1.125rem;
	background-color: white;

	transition: all 0.2s;
}

.filter > .filter-title:is(:hover, .active) {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.filter > .filter-body {
	box-sizing: border-box;
	position: absolute;
	top: 55px;
	left: 0px;
	display: flex;
	flex-direction: column;

	padding: 10px;
	border-radius: 10px;
	min-width: 300px;
	max-width: 600px;

	opacity: 0;
	visibility: hidden;

	background-color: white;
	box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.1);
}

.filter > .filter-body > .container-input {
	display: flex;
	flex-direction: column;
}

.filter > .filter-body > .container-input > input {
	box-sizing: border-box;

	outline: none;
	padding: 10px;
	border: 0px solid white;
	border-radius: 10px;

	font-size: 1.125rem;

	background-color: rgba(0, 0, 0, 0.05);
}

.filter > .filter-body.active {
	opacity: 1;
	visibility: visible;
}

.filter > .filter-body > ol {
	list-style: none;
	display: flex;
	flex-direction: column;
	gap: 5px;

	width: auto;
	max-height: 420px;
	overflow-y: auto;

	font-size: 1.125rem;

	padding: 0px;
	margin: 10px 0px 0px 0px;
}

/* Скролбар блока с контентом. */
.filter > .filter-body > ol::-webkit-scrollbar {
	width: 20px;
}

.filter > .filter-body > ol::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
	border-radius: 10px;
	margin: 2px;
}

.filter > .filter-body > ol::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 255, 255);
	border-radius: 10px;
}

.filter > .filter-body > ol::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}
/* Конец. */

.filter > .filter-body > ol > li {
	cursor: pointer;
	padding: 10px;
	white-space: nowrap;
}

.filter > .filter-body > ol > li:hover {
	padding: 10px;
	border-radius: 10px;
	background-color: rgba(0, 0, 0, 0.05);
}
</style>
