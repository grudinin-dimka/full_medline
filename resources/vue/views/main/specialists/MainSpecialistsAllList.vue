<template>
	<div class="specialists__list">
		<div class="specialists__list-item" v-for="specialist in specialists" :key="specialist.id">
			<img class="specialists__list-img" :src="specialist.path" alt="Фото специалиста" />

			<div class="specialists__list-body">
				<div class="specialists__body-options">
					<div class="specialists__body-other">
						{{ getSpecializationString(specialist.specialization) }}.
					</div>
					<div class="clinics__item-title label" @click="openspecialistProfile(specialist)">
						{{ specialist.name }}
					</div>
				</div>

				<div class="specialist__body-clinics" v-if="specialist.clinics.length > 0">
					<div class="specialist__clinics-item" v-for="clinic in specialist.clinics">
						<div class="specialists__body-other">
							{{ `г. ${clinic.city}, ул. ${clinic.street}, д. ${clinic.home}` }}.
						</div>
						<div class="clinics__item-title label" @click="openspecialistProfile(specialist)">
							{{ clinic.name }}
						</div>
					</div>
				</div>

				<div class="specialist__body-clinics" v-else>
					<div class="specialist__clinics-item">
						<div class="specialists__body-other">Неизвестно.</div>
						<div class="clinics__item-title label" @click="openspecialistProfile(specialist)">
							Не указано.
						</div>
					</div>
				</div>
				<a
					@click.prevent="openspecialistProfile(specialist)"
					:href="`/specialists/${specialist.url}`"
				>
					Подробнее
				</a>
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
			let specializationString = "";

			sorted.sortStringByKey("up", specialization, "name");

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
				name: "specialists-profile",
				params: { name: specialist.url, catagory: null },
			});
		},
	},
};
</script>

<style scoped>
.specialists__list {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.specialists__list-item {
	box-sizing: border-box;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 20px;

	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);
	padding: 0px 0px 20px 0px;

	width: 100%;
	transition: all 0.2s;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.specialists__list-img {
	width: 90px;
	border-radius: 300px;
	border: var(--default-border);
	height: 90px;
	object-fit: contain;
}

.specialists__list-body {
	flex: 1 0 0px;
	display: grid;
	width: 100%;
	grid-template-columns: 1fr 1fr 150px;
	gap: 20px;
	align-items: center;
}

.specialist__body-clinics {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist__body-clinics > .specialist__clinics-item.empty {
	font-size: 1.125rem;
}

.specialists__list-body > a {
	cursor: pointer;
	padding: 15px;
	font-size: 1.125em;

	text-align: center;
	text-decoration: none;

	border-radius: 10px;
	color: white;
	background-color: var(--button-background-color);

	transition: all 0.2s;
}

.specialists__list-body > a:hover {
	background-color: var(--button-background-color-hover);
	color: white;
}

.specialists__body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;
}

.clinics__item-title {
	font-size: 24px;

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

	.specialists__list-img {
		width: 200px;
		border-radius: 300px;
		border: var(--default-border);
		height: 200px;
		object-fit: contain;
	}
}

@media screen and (max-width: 440px) {
	.specialists__body-other {
		display: none;
	}

	.specialists__list-img {
		width: 100%;
		border-radius: 300px;
		border: var(--default-border);
		height: auto;
		aspect-ratio: 1/1;
		max-width: 300px;
		object-fit: contain;
	}

	.specialists__list-body {
		text-align: center;
		grid-template-columns: 1fr;
	}
}
</style>
