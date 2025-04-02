<template>
	<button class="shedule_refresh" @click="updateSheduleFromServe">
		<svg
			xmlns="http://www.w3.org/2000/svg"
			height="45px"
			viewBox="0 -960 960 960"
			width="45px"
			fill="white"
		>
			<path
				d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
			/>
		</svg>
	</button>
</template>

<script>
import axios from 'axios';

export default {
	methods: {
		async updateSheduleFromServe() {
			await axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-shedules-all`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					type: "manual",
				},
			})
				.then((response) => {
					console.log(response.data);
				})
				.catch((error) => {
					console.log(error);
				});
		},
	},
};
</script>

<style scoped>
.shedule_refresh {
	cursor: pointer;
	position: fixed;
	right: 30px;
	bottom: 30px;
	z-index: 10;
	width: 80px;
	height: 80px;
	border-radius: 100px;
	border: none;
	background-color: var(--button-default-color);
	color: white;
}

.shedule_refresh:hover {
	background-color: var(--button-default-color-hover);
}
</style>
