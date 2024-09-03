<template>
	<div class="doctor" v-for="doctor in doctors" :key="doctor.id">
		<img :src="doctor.img" />
		<div class="doctor-body">
			<div class="doctor-body-options">
				<div class="doctor-body-options-title">{{ doctor.name }}</div>
				<div class="doctor-body-options-other">
					<div class="doctor-body-options-other-block">
						<div>Специальность:</div>
						<div>{{ doctor.specialization }}</div>
					</div>
					<div class="doctor-body-options-other-block">
						<div>Стаж:</div>
						<div>{{ doctor.startWorkAge }}</div>
					</div>
					<div class="doctor-body-options-other-block">
						<div>Образование:</div>
						<div>
							{{ doctor.education }}
						</div>
					</div>
					<div class="doctor-body-options-other-block">
						<div>Сертификаты:</div>
						<div>
							{{ doctor.certificates }}
						</div>
					</div>
				</div>
			</div>
			<div class="doctor-body-link" @click="openDoctorProfile(doctor.id)">
				Подробнее
			</div>
		</div>
	</div>
</template>

<script>
export default {
	components: {},
	props: {
		doctors: {
			type: Array,
			default: () => [],
			Required: true,
		},
	},
	methods: {
		openDoctorProfile(id) {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "smooth",
			});
			this.$router.push("/specialists/" + id);
		},
	},
};
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
	transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
	opacity: 0;
	transform: translateX(30px);
}

.doctor {
	box-sizing: border-box;
	display: flex;
	justify-content: center;
	gap: 20px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 20px;
	padding: 20px;

	width: 900px;

   animation: show 0.5s ease-out;
	transition: all 0.2s;
}

.doctor > img {
	width: 230px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.doctor-body {
	flex: 1 0 0px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

.doctor-body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;
}

.doctor-body-options-title {
	font-size: 24px;
}

.doctor-body-options-other {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.doctor-body-options-other-block {
	display: grid;
	grid-template-columns: 150px 1fr;
}

.doctor-body-options-other-block > div {
	font-size: 16px;
}

.doctor-body-price {
	font-size: 20px;
	text-align: right;
}

.doctor-body-advanced {
	display: flex;
	justify-content: space-between;
}

.doctor-body-link {
	margin-top: 10px;
	font-size: 16px;
	color: var(--primary-color);
	cursor: pointer;
	text-align: right;
}

@keyframes show {
   0% {
      opacity: 0;
      transform: translateY(30px);
   }
   100% {
      opacity: 1;
      transform: translateY(0);
   }
}

@media screen and (max-width: 980px) {
	.doctor {
		width: 100%;
	}
}

@media screen and (max-width: 775px) {
	.doctor {
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
}
</style>
