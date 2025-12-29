<template>
	<div class="specialists">
		<div class="specialists__list">
			<div
				class="specialists__list-item"
				v-for="specialist in specialists"
				:key="specialist.id"
				:class="{ skeleton: !specialist.path }"
			>
				<a
					class="specialists__img"
					:href="`/specialists/${specialist.url}`"
					@click.prevent="openspecialistProfile(specialist)"
				>
					<img
						v-if="specialist.path"
						class="specialists__list-img"
						:src="specialist.path"
						alt="Фото специалиста"
						loading="lazy"
					/>
				</a>

				<div class="specialists__list-body">
					<div class="specialists__body-options">
						<div class="clinics__item-title label" @click="openspecialistProfile(specialist)">
							{{ specialist.name ?? "" }}
						</div>
						<div class="specialists__body-other">
							{{ getSpecializationString(specialist.specialization) }}
						</div>
					</div>

					<div class="specialists__body-buttons">
						<div class="specialists__body-age">
							{{ getWorkAges(specialist.startWorkAge) }}
						</div>

						<a
							class="specialists__body-more"
							@click.prevent="openspecialistProfile(specialist)"
							:href="`/specialists/${specialist.url}`"
						>
							Подробнее
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import sorted from "../../../services/sorted";

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
			if (!specialization) {
				return "";
			}

			let specializationString = "";

			sorted.sortStringByKey("up", specialization, "name");

			for (let i = 0; i < specialization.length; i++) {
				specializationString += specialization[i].name + ", ";
			}

			return specializationString.slice(0, -2) ? specializationString.slice(0, -2) : "Нет информации";
		},

		getWorkAges(date) {
			if (!date) {
				return "Менее года.";
			}

			let startDate = new Date(date);
			let currentDate = new Date();
			let year = currentDate.getFullYear() - startDate.getFullYear();

			// Получаем последние две цифры года
			const lastTwoDigits = year % 100;
			// Получаем последнюю цифру
			const lastDigit = year % 10;

			// Исключения для чисел 11-19
			if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
				return "лет";
			}

			// Для остальных случаев
			switch (lastDigit) {
				case 1:
					return year + " год";
				case 2:
				case 3:
				case 4:
					return year + " года";
				default:
					return year + " лет";
			}
		},

		openspecialistProfile(specialist) {
			if (!specialist.url) {
				return;
			}

			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});

			this.$router.push({
				name: "specialists-profile",
				params: { name: specialist.url },
			});
		},
	},
};
</script>

<style scoped>
/* Блок специалистов */
.specialists {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1350px;
}

.specialists > .specialists__header {
	display: grid;
	grid-template-columns: 110px 1fr 1fr 150px;
	align-items: center;

	color: rgb(150, 150, 150);
	font-size: 1.125rem;
}

.specialists__list {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 20px;
}

.specialists__list-item {
	box-sizing: border-box;
	display: flex;
	gap: 20px;

	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: var(--default-border-radius);

	width: 100%;
	background-color: var(--skeleton-background-color);

	transition: all 0.2s;
}

.specialists__img {
	display: block;

	width: 200px;
	height: 200px;

	border: var(--default-border);
	border-radius: 300px;

	background-color: white;
}

.specialists__list-item:hover {
	background-image: linear-gradient(
		120deg,
		var(--skeleton-background-color) 50%,
		var(--skeleton-line-color) 60%,
		var(--skeleton-line-color) 61%,
		var(--skeleton-background-color) 70%
	);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

.specialists__list-img {
	width: 100%;
	border-radius: 300px;
	height: 100%;
	object-fit: contain;

	animation: show 0.5s ease-out;
}

.specialists__list-body {
	flex: 1 0 0px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	width: 100%;

	gap: var(--default-gap);
}

.specialist__body-clinics {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist__clinics-item {
	font-size: 1.125rem;

	border-radius: 10px;
	padding: 5px;

	min-height: 50px;
}

.specialist__body-clinics > .specialist__clinics-item.empty {
	font-size: 1.125rem;
}

.specialists__body-buttons {
	display: flex;
	justify-content: space-between;

	gap: calc(var(--default-gap) / 2);
}

.specialists__body-age {
	cursor: default;

	padding: var(--button-padding);
	border-radius: var(--button-border-radius);

	color: var(--primary-color);
	background-color: var(--transparent-color);
}

.specialists__body-more {
	cursor: pointer;
	padding: var(--button-padding);
	font-size: 1.125em;

	text-align: center;
	text-decoration: none;

	border-radius: var(--button-border-radius);
	color: white;
	background-color: var(--button-background-color);

	transition: all 0.2s;
}

.specialists__body-more:hover {
	background-color: var(--button-background-color-hover);
	color: white;
}

.specialists__body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;

	border-radius: 10px;
	padding: 5px;

	min-height: 50px;
}

.clinics__item-title {
	font-size: 24px;
	color: var(--primary-color);

	transition: all 0.2s;
}

.clinics__item-title.label {
	cursor: pointer;
}

.clinics__item-title.label:hover {
	color: var(--primary-color);
}

.specialists__body-other {
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

@media screen and (width < 1450px) {
	.specialists {
		width: 100%;
	}
}

@media screen and (max-width: 1200px) {
	.specialists__list {
		grid-template-columns: 1fr;
	}
}

@media screen and (max-width: 1100px) {
	.specialists__list-body {
		grid-template-columns: 1fr 150px;
	}

	.specialist__body-clinics {
		display: none;
	}
}

@media screen and (max-width: 600px) {
	.specialists__list-item {
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	.specialists__img {
		width: 300px;
		height: 300px;
	}

	.specialists__list-img {
		width: 100%;
		border-radius: 300px;
		height: 100%;
		object-fit: contain;
	}
}

@media screen and (max-width: 500px) {
	.specialists__list-body {
		text-align: center;
		grid-template-columns: 1fr;
	}
}
</style>
