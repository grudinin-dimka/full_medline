<template>
	<div class="contacts">
		<div
			class="contacts__item"
			v-for="contact in contacts"
			:class="{ one: contact.clinicId == null }"
		>
			<div class="contacts__info">
				<div class="contacts__info-title">{{ contact.name }}</div>
				<div class="contacts__info-body">
					<div class="contacts__phones">
						<div class="contacts__phones-title">Телефоны:</div>
						<ul>
							<li v-for="phone in contact.phones" v-if="contact.phones.length > 0">
								<VueIcon :name="'phone'" :fill="'black'" :width="'16px'" :height="'16px'" />
								<a :href="`tel:${phone.name}`" :key="phone.id">{{ phone.name }}</a>
							</li>
							<li v-else>
								<VueIcon :name="'phone'" :fill="'black'" :width="'16px'" :height="'16px'" />
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="contacts__mails">
						<div class="contacts__mails-title">Почта:</div>
						<ul>
							<li v-for="mail in contact.mails" v-if="contact.mails.length > 0">
								<VueIcon :name="'mail'" :fill="'black'" :width="'18px'" :height="'18px'" />
								<a :href="`mailto:${mail.name}`">{{ mail.name }}</a>
							</li>
							<li v-else>
								<VueIcon :name="'mail'" :fill="'black'" :width="'14px'" :height="'14px'" />
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="contacts__address" v-if="contact.clinicId != null">
						<div class="contacts__address-title">Адрес:</div>
						<ul>
							<li>
								<VueIcon :name="'home'" :fill="'black'" :width="'20px'" :height="'18px'" />
								{{
									`г. ${contact.clinic.city}, ул. ${contact.clinic.street}, д. ${contact.clinic.home}`
								}}
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="contacts__map" v-if="contact.clinicId != null" :id="`map-${contact.id}`">
				<div
					class="contacts__map-body"
					:id="`map-container-${contact.id}`"
					v-if="true"
					style="width: 100%; height: 100%; border-radius: 10px"
				></div>
				<div
					class="contacts__map-wall"
					@click="showMap(contact.id, [contact.clinic.geoLongitude, contact.clinic.geoWidth])"
				>
					<svg viewBox="0 0 640 313" xmlns="http://www.w3.org/2000/svg">
						<rect x="104" width="12" height="313" />
						<rect x="313" y="135" width="12" height="98" />
						<rect x="232" width="12" height="123" />
						<rect x="475" width="12" height="123" />
						<rect y="186" width="12" height="313" transform="rotate(-90 0 186)" />
						<rect x="232" y="135" width="12" height="255" transform="rotate(-90 232 135)" />
						<rect x="313" y="245" width="12" height="327" transform="rotate(-90 313 245)" />
						<rect x="126" y="196" width="54" height="54" rx="5" />
						<rect x="65" y="196" width="29" height="100" rx="5" />
						<rect x="26" y="195" width="29" height="100" rx="5" />
						<rect x="147" y="36" width="75" height="35" rx="5" />
						<rect x="335" y="145" width="54" height="78" rx="5" />
						<rect x="20" y="8" width="74" height="115" rx="5" />
						<rect x="497" y="10" width="54" height="125" rx="5" />
						<rect x="561" y="10" width="54" height="125" rx="5" />
						<rect x="190" y="81" width="32" height="83" rx="5" />
						<rect x="148" y="81" width="32" height="83" rx="5" />
						<rect x="190" y="196" width="113" height="54" rx="5" />
						<rect x="126" y="259" width="187" height="17" rx="5" />
						<rect x="126" y="286" width="187" height="17" rx="5" />
						<rect x="232" y="145" width="71" height="19" rx="5" />
						<rect x="399" y="169" width="165" height="54" rx="5" />
						<rect x="335" y="255" width="280" height="48" rx="5" />
						<rect x="254" y="31" width="188" height="82" rx="5" />
					</svg>
					<div class="contacts__map-wall-title">Показать на карте</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
async function initMap(mapId, coordinates) {
	ymaps3.ready.then(() => {
		let { YMap, YMapDefaultSchemeLayer, YMapMarker, YMapDefaultFeaturesLayer, Placemark } =
			ymaps3;

		let map = new YMap(document.querySelector("#map-container-" + mapId), {
			location: {
				center: coordinates,
				zoom: 17,
			},
		});
		map.addChild(new YMapDefaultSchemeLayer());

		// Создание маркера
		const el = document.createElement("div");
		el.className = "my-marker";
		el.title = "Маркер";

		// При клике на маркер меняем центр карты на LOCATION с заданным duration
		el.onclick = () => map.update({ location: { ["center"]: coordinates, duration: 400 } });

		// Создание заголовка маркера
		const markerTitle = document.createElement("span");
		markerTitle.className = "marker-title";

		// Контейнер для элементов маркера
		const imgContainer = document.createElement("div");
		imgContainer.appendChild(el);
		imgContainer.appendChild(markerTitle);

		const content = document.createElement("span");
		content.innerHTML = "<span>Мы находимся здесь</span>";
		map.addChild(new YMapDefaultFeaturesLayer({ zIndex: 1800 }));
		map.addChild(new YMapMarker({ coordinates: coordinates }, imgContainer));
	});
}

import VueIcon from "../../../components/modules/icon/VueIcon.vue";

export default {
	components: {
		VueIcon,
	},
	props: {
		contacts: {
			type: Array,
			required: true,
			default: [],
		},
	},
	data() {
		return {
			mapTitle: true,
			mapBody: false,
		};
	},
	methods: {
		async showMap(mapId, coordinates) {
			let map = document.querySelector("#map-" + mapId);
			let mapWall = map.children[1];
			let svgMapWall = mapWall.children[0];
			let titleMapWall = mapWall.children[1];

			map.classList.add("load");
			mapWall.classList.add("load");
			svgMapWall.classList.add("load");
			titleMapWall.classList.add("load");

			await initMap(mapId, coordinates)
				.catch(() => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Карта не загрузилась.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
					return;
				})
				.finally(() => {
					map.classList.remove("load");
					mapWall.classList.remove("load");
					svgMapWall.classList.remove("load");
					titleMapWall.classList.remove("load");

					mapWall.classList.add("active");
				});
		},
	},
};
</script>

<style>
.contacts {
	width: 1350px;
	display: flex;
	flex-direction: column;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.contacts__item {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 20px;
	font-size: 18px;
}

.contacts__item.one {
	grid-template-columns: 1fr;
}

.contacts__info {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts__info-body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts__item.one > .contacts__info > .contacts__info-body {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 10px;
}

.contacts__info-title {
	font-size: 22px;
	font-weight: bold;
	color: var(--primary-color);
}

:is(.contacts__mails, .contacts__phones, .contacts__address) > ul {
	padding: 0px;
	margin: 0px 0px 0px 20px;
	inline-size: 80%;

	overflow-y: auto;
}

:is(.contacts__mails, .contacts__phones, .contacts__address) > ul > li {
	display: flex;
	align-items: center;
	word-wrap: break-word;
	gap: 5px;

	margin-top: 10px;
}

:is(.contacts__mails, .contacts__phones, .contacts__address) > ul > li > svg {
	flex: 0 0 20px;
}

:is(.contacts__mails, .contacts__phones, .contacts__address) > ul > li > a {
	text-decoration: none;
	text-decoration-color: white;
	color: black;

	transition: all 0.2s;
}

:is(.contacts__mails, .contacts__phones, .contacts__address) > ul > li > a:hover {
	text-decoration: underline;
	text-decoration-thickness: 1.5px;
	text-decoration-color: var(--primary-color);
}

ul::-webkit-scrollbar {
	width: 18px;
	transition: all 0.2s;
}

ul::-webkit-scrollbar-track {
	background-color: rgba(255, 255, 255, 0);
	border-radius: 10px;
}

ul::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #ffffff;
	border-radius: 10px;
}

ul::-webkit-scrollbar-thumb:hover {
	background-color: rgb(210, 210, 210);
	cursor: all-scroll;
}

span.empty {
	color: rgb(199, 199, 199);
}

/* Map */
.my-marker {
	background-image: url("../../../assets/svg/map-point.svg");
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;
	width: 30px;
	height: 30px;
	cursor: pointer;
}

.contacts__map {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	overflow: hidden;

	min-height: 300px;
	height: 300px;

	border: var(--input-border);
	border-radius: var(--input-border-radius);

	transition: all 0.2s;
}

.contacts__map:hover {
	border: var(--input-border-focus);
}

.contacts__map-wall-title {
	position: absolute;

	padding: 10px;
	background-color: white;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	transition: all 0.2s;
}

.contacts__map-wall {
	cursor: pointer;
	position: absolute;
	display: flex;
	justify-content: center;
	align-items: center;
	top: 0px;

	overflow: hidden;
	color: var(--input-border-color-inactive);
	background-color: white;

	min-width: 100%;
	height: 300px;

	transition: top 1s, background-color 0.2s;
}

.contacts__map-wall:hover {
	background-color: var(--item-background-color-active);
}

.contacts__map-wall > svg {
	width: 100%;
	min-width: 620px;
	min-height: 100%;

	fill: #f2f2f2;
	transition: all 0.2s;
}

.contacts__map-wall:hover > svg {
	fill: var(--secondary-color);
}

.contacts__map-wall:hover > .contacts__map-wall-title {
	border: var(--input-border-focus);
	color: var(--primary-color);
}

.contacts__map.load {
	animation: load-map 2s infinite ease-in-out;
}

.contacts__map-wall.load {
	animation: load-map-wall 2s infinite ease-in-out;
}

.contacts__map-wall.active {
	top: -300px;
}

.contacts__map-wall > svg.load {
	animation: load-map-svg 2s infinite ease-in-out;
}

.contacts__map-wall-title.load {
	animation: load-map-title 2s infinite ease-in-out;
}

/* Анимации */
@keyframes load-map {
	0%,
	50%,
	100% {
		border-color: var(--input-border-color-active);
	}

	25%,
	75% {
		border-color: var(--input-border-color-inactive);
	}
}

@keyframes load-map-wall {
	0%,
	50%,
	100% {
		background-color: #f2feff;
	}

	25%,
	75% {
		background-color: white;
	}
}

@keyframes load-map-svg {
	0%,
	50%,
	100% {
		fill: #dbf1f3;
	}

	25%,
	75% {
		fill: #f2f2f2;
	}
}

@keyframes load-map-title {
	0%,
	50%,
	100% {
		color: var(--primary-color);
		border-color: var(--input-border-color-active);
		background-color: #f2feff;
	}

	25%,
	75% {
		color: var(--input-border-color-inactive);
		border-color: var(--input-border-color-inactive);
		background-color: white;
	}
}

/* Адаптив */
@media screen and (width <= 1430px) {
	.contacts {
		width: 100%;
	}
}

@media screen and (width <= 970px) {
	.contacts__item {
		grid-template-columns: repeat(1, 1fr);
		gap: 20px;
		font-size: 18px;
	}

	.contacts__info-body {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
	}

	.contacts__item.one > .contacts__info > .contacts__info-body {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (width <= 820px) {
	.contacts__info-body,
	.contacts__item.one > .contacts__info > .contacts__info-body {
		grid-template-columns: repeat(1, 1fr);
	}
}
</style>
