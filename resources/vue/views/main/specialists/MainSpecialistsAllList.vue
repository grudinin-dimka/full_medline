<template>
	<div class="specialists__list">
		<div class="specialists__list-item" v-for="specialist in specialists" :key="specialist.id">
			<a
				class="specialists__img"
				:href="`/specialists/${specialist.url}`"
				:class="{ skeleton: !specialist.path }"
				@click.prevent="openspecialistProfile(specialist)"
			>
				<img
					v-if="specialist.path"
					class="specialists__list-img"
					:src="specialist.path"
					alt="Фото специалиста"
				/>
			</a>

			<div class="specialists__list-body">
				<div class="specialists__body-options" :class="{ skeleton: !specialist.name }">
					<div class="specialists__body-other">
						{{ getSpecializationString(specialist.specialization) }}
					</div>
					<div class="clinics__item-title label" @click="openspecialistProfile(specialist)">
						{{ specialist.name ?? "" }}
					</div>
				</div>

				<div
					class="specialist__body-clinics"
					v-if="specialist.clinics && specialist.clinics.length > 0"
				>
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
					<div class="specialist__clinics-item" :class="{ skeleton: !specialist.name }">
						<template v-if="!specialist.name">
							<div class="specialists__body-other"></div>
							<div class="clinics__item-title label"></div>
						</template>

						<template v-else>
							<div class="specialists__body-other">Неизвестно.</div>
							<div
								class="clinics__item-title label"
								@click="openspecialistProfile(specialist)"
							>
								Не указано.
							</div>
						</template>
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
			if (!specialization) {
				return "";
			}

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
			if (!date) {
				return "Менее года.";
			}

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
				params: { name: specialist.url, category: null },
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

	padding: 0px 0px 20px 0px;

	width: 100%;
	transition: all 0.2s;
}

.specialists__img {
	display: block;

	width: 90px;
	height: 90px;
	border-radius: 300px;

	background-color: var(--skeleton-background-color);
}

.specialists__list-item:hover .specialists__img {
	background-image: linear-gradient(120deg, #ececec 50%, #fafafa 60%, #fafafa 61%, #ececec 70%);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

.specialists__list-img {
	width: 90px;
	border-radius: 300px;
	height: 90px;
	object-fit: contain;

	animation: show 0.5s ease-out;
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

.specialist__clinics-item {
	font-size: 1.125rem;

	border-radius: 10px;
	padding: 5px;

	min-height: 50px;
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

	border-radius: 10px;
	padding: 5px;

	min-height: 50px;
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

	.specialists__img {
		width: 200px;
		height: 200px;
	}

	.specialists__list-img {
		width: 200px;
		border-radius: 300px;
		height: 200px;
		object-fit: contain;
	}
}

@media screen and (max-width: 440px) {
	.specialists__body-other {
		display: none;
	}

	.specialists__img {
		width: auto;
		height: auto;
	}

	.specialists__list-img {
		width: 100%;
		height: auto;
		aspect-ratio: 1/1;
		object-fit: contain;
	}

	.specialists__list-body {
		text-align: center;
		grid-template-columns: 1fr;
	}
}
</style>
