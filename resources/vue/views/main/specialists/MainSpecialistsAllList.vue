<template>
	<!-- TODO сделать отображение специализаций -->
	<div class="specialist" v-for="specialist in specialists" :key="specialist.id">
		<img :src="specialist.path" />
		<div class="specialist-body">
			<div class="specialist-body-options">
				<div class="specialist-body-options-title">
					{{ specialist.name }}
				</div>
				<div class="specialist-body-options-other">
					<div class="specialist-body-options-other-block">
						<div>Специализация:</div>
						<div>{{ getSpecializationString(specialist.specialization) }}.</div>
					</div>
					<div class="specialist-body-options-other-block">
						<div>Категория:</div>
						<div>{{ specialist.category }}.</div>
					</div>
					<div class="specialist-body-options-other-block">
						<div>Стаж:</div>
						<div>{{ getWorkAges(specialist.startWorkAge) }}</div>
					</div>
					<div class="specialist-body-options-other-block" v-if="specialist.childrenDoctor">
						<div>Приём у детей:</div>
						<div>{{ specialist.childrenDoctorAge }}+.</div>
					</div>
					<div class="specialist-body-options-other-block" v-else>
						<div>Приём у детей:</div>
						<div>Нет.</div>
					</div>
					<div class="specialist-body-options-other-block">
						<div>Приём у взрослых:</div>
						<div>{{ specialist.adultDoctor ? "Да." : "Нет." }}</div>
					</div>
				</div>
			</div>
			<div class="specialist-body-link" @click="openspecialistProfile(specialist)">
				Подробнее
			</div>
		</div>
	</div>
</template>

<script>
export default {
	components: {},
	props: {
		specialists: {
			type: Array,
			default: () => [],
			Required: true,
		},
	},
	methods: {
		getSpecializationString(specialization) {
			let specializationString = "";

			for (let i = 0; i < specialization.length; i++) {
				specializationString += specialization[i].name + ", ";
			}

			return specializationString.slice(0, -2)
				? specializationString.slice(0, -2)
				: "Нет информации";
		},
		getWorkAges(date) {
			let startDate = new Date(date);
			let currentDate = new Date();
			let result = currentDate.getFullYear() - startDate.getFullYear();
			if (result == 0) return "Менее года.";
			switch (result) {
				case 1:
					return result + " " + "год.";
					break;
				case 2:
				case 3:
				case 4:
					return result + " " + "года.";
					break;
				default:
					return result + " " + "лет.";
					break;
			}
		},
		openspecialistProfile(specialist) {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});

			this.$router.push({
				name: "doctorname",
				params: { name: specialist.url, catagory: null },
			});
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

.specialist {
	box-sizing: border-box;
	display: flex;
	justify-content: center;
	gap: 20px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 20px;
	padding: 20px;

	min-height: 312.7px;

	width: 100%;
	transition: all 0.2s;
}

.specialist > img {
	width: 230px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.specialist-body {
	flex: 1 0 0px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

.specialist-body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;
}

.specialist-body-options-title {
	font-size: 24px;
}

.specialist-body-options-other {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist-body-options-other-block {
	display: grid;
	grid-template-columns: 175px 1fr;
}

.specialist-body-options-other-block > div {
	font-size: 18px;
}

.specialist-body-price {
	font-size: 20px;
	text-align: right;
}

.specialist-body-advanced {
	display: flex;
	justify-content: space-between;
}

.specialist-body-link {
	cursor: pointer;

	margin-top: 10px;
	margin-left: auto;

	font-size: 16px;
	color: var(--primary-color);

	width: 100px;

	text-align: right;
}

.specialist-body-link:hover {
	text-decoration: underline;
}

@media screen and (max-width: 775px) {
	.specialist {
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
}

@media screen and (max-width: 440px) {
	.specialist-body-options-other {
		display: none;
	}

	.specialist > img {
		width: 100%;
		max-width: 230px;
	}
}
</style>
