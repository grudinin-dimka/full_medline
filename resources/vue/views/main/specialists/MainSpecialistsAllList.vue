<template>
	<div class="specialist" v-for="specialist in specialists" :key="specialist.id">
		<div class="specialist-img" :style="{ backgroundImage: `url(${specialist.path})` }"></div>
		<div class="specialist-body">
			<div class="specialist-body-options">
				<div class="specialist-body-options-other">
					{{ getSpecializationString(specialist.specialization) }}.
				</div>
				<div
					class="specialist-body-options-title label"
					@click="openspecialistProfile(specialist)"
				>
					{{ specialist.name }}
				</div>
			</div>
			<div class="specialist-body-clinics" v-if="specialist.clinics.length > 0">
				<div class="clinic" v-for="clinic in specialist.clinics">
					<div class="specialist-body-options-other">
						{{ `г. ${clinic.city}, ул. ${clinic.street}, д. ${clinic.home}` }}.
					</div>
					<div
						class="specialist-body-options-title label"
						@click="openspecialistProfile(specialist)"
					>
						{{ clinic.name }}
					</div>
				</div>
			</div>
			<div class="specialist-body-clinics" v-else>
				<div class="clinic">
					<div class="specialist-body-options-other">Неизвестно.</div>
					<div
						class="specialist-body-options-title label"
						@click="openspecialistProfile(specialist)"
					>
						Не указано.
					</div>
				</div>
			</div>
			<a @click.prevent="openspecialistProfile(specialist)" :href="`/specialists/${specialist.url}`">Подробнее</a>
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

	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: var(--input-border-color-inactive);
	padding: 0px 0px 20px 0px;

	width: 100%;
	transition: all 0.2s;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.specialist > :is(img, .specialist-img) {
	width: 90px;
	border-radius: 300px;
	border: 2px solid var(--input-border-color-inactive);
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 90px;
}

.specialist > :is(img:hover, .specialist-img:hover) {
	border: 2px solid var(--input-border-color-inactive);
}

.specialist-body {
	flex: 1 0 0px;
	display: grid;
	grid-template-columns: 1fr 1fr 150px;
	gap: 20px;
	align-items: center;
}

.specialist-body-clinics {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist-body-clinics > .clinic.empty {
	font-size: 1.125rem;
}

.specialist-body > a {
	cursor: pointer;
	padding: 15px;
	font-size: 1.125em;

	text-align: center;
	text-decoration: none;

	border-radius: 10px;
	color: white;
	background-color: var(--button-default-color);

	transition: all 0.2s;
}

.specialist-body > a:hover {
	background-color: var(--button-default-color-hover);
	color: white;
}

.specialist-body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;
}

.specialist-body-options-title {
	font-size: 24px;

	transition: all 0.2s;
}

.specialist-body-options-title.label {
	cursor: pointer;
}

.specialist-body-options-title.label:hover {
	color: var(--primary-color);
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

@media screen and (max-width: 1100px) {
	.specialist-body {
		grid-template-columns: 1fr 150px;
	}

	.specialist-body-clinics {
		display: none;
	}
}

@media screen and (max-width: 600px) {
	.specialist {
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	.specialist > :is(img, .specialist-img) {
		width: 150px;
		border-radius: 300px;
		background-size: contain;
		background-position: center center;
		background-repeat: no-repeat;
		height: 150px;
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

	.specialist-body {
		text-align: center;
		grid-template-columns: 1fr;
	}
}
</style>
