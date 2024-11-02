<template>
	<div class="contacts-main">
		<div class="item" v-for="contact in contacts" :class="{ one: contact.clinicId == null }">
			<div class="info">
				<div class="title">{{ contact.name }}</div>
				<div class="body">
					<div class="phone">
						<div class="title">Телефоны:</div>
						<ul>
							<li v-for="phone in contact.phones" v-if="contact.phones.length > 0">
								<IconContactPhone :width="16" :height="16" />
								<a :href="`tel:${phone.name}`" :key="phone.id">{{ phone.name }}</a>
							</li>
							<li v-else>
								<IconContactPhone :width="16" :height="16" />
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="mail">
						<div class="title">Почта:</div>
						<ul>
							<li v-for="mail in contact.mails" v-if="contact.mails.length > 0">
								<IconContactMail :width="18" :height="16" />
								<a :href="`mailto:${mail.name}`">{{ mail.name }}</a>
							</li>
							<li v-else>
								<IconContactMail :width="18" :height="16" />
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="address" v-if="contact.clinicId != null">
						<div class="title">Адрес:</div>
						<ul>
							<li>
								<IconContactHome :width="18" :height="18" />
								{{
									`г. ${contact.clinic.city}, ул. ${contact.clinic.street}, д. ${contact.clinic.home}`
								}}
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="map" v-if="contact.clinicId != null" :id="`map-${contact.id}`">
				<div
					class="body"
					:id="`map-container-${contact.id}`"
					v-if="true"
					style="width: 100%; height: 100%; border-radius: 10px"
				></div>
				<div
					class="wall"
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
					<div class="title">Показать карту</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import IconContactMail from "../../../components/icons/contacts/IconContactMail.vue";
import IconContactPhone from "../../../components/icons/contacts/IconContactPhone.vue";
import IconContactHome from "../../../components/icons/contacts/IconContactHome.vue";

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

export default {
	components: {
		IconContactMail,
		IconContactPhone,
		IconContactHome,
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
		showMap(mapId, coordinates) {
			let element = document.querySelector("#map-" + mapId);
			let elementWall = element.children[1];
			elementWall.classList.add("active");

			initMap(mapId, coordinates);
		},
	},
};
</script>

<style>
.my-marker {
	background-image: url("../../../assets/svg/map-point.svg");
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;
	width: 30px;
	height: 30px;
	cursor: pointer;
}

.contacts-main {
	width: 1250px;
	display: flex;
	flex-direction: column;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.contacts-main > .item {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 20px;
	font-size: 18px;
}

.contacts-main > .item.one {
	grid-template-columns: 1fr;
}

.contacts-main > .item > .info {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts-main > .item > .info > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts-main > .item.one > .info > .body {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 20px;
}

.contacts-main > .item > .info > .title {
	font-size: 22px;
	font-weight: bold;
	color: var(--primary-color);
}

.contacts-main > .item > .map {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 300px;
	overflow: hidden;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	transition: all 0.2s;
}

.contacts-main > .item > .map:hover {
	border: 2px solid var(--input-border-color-active);
}

.contacts-main > .item > .map > .wall > .title {
	position: absolute;

	padding: 10px;
	background-color: white;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	transition: all 0.2s;
}

.contacts-main > .item > .map > .wall {
	cursor: pointer;
	position: absolute;
	display: flex;
	justify-content: center;
	align-items: center;
	top: 0px;

	overflow: hidden;
	color: var(--input-border-color-inactive);
	background-color: white;

	width: 100%;
	height: 300px;

	transition: top 1s, background-color 0.2s;
}

.contacts-main > .item > .map > .wall:hover {
	background-color: #f2feff;
}

.contacts-main > .item > .map > .wall > svg {
	width: 100%;
	min-width: 600px;
	height: 300px;
	min-height: 100%;

	fill: #f2f2f2;
	transition: all 0.2s;
}

.contacts-main > .item > .map > .wall:hover > svg {
	fill: #dbf1f3;
}

.contacts-main > .item > .map > .wall:hover > .title {
	border: 2px solid var(--input-border-color-active);
	color: var(--primary-color);
}

.contacts-main > .item > .map > .wall.active {
	top: -300px;
}

.contacts-main > .item > .info > .body > :is(.mail, .phone, .address) > ul {
	padding: 0px;
	margin: 0px 0px 0px 20px;
	inline-size: 80%;

	overflow-y: auto;
}

.contacts-main > .item > .info > .body > :is(.mail, .phone, .address) > ul > li {
	display: flex;
	align-items: center;
	word-wrap: break-word;
	gap: 5px;

	margin-top: 10px;
}

.contacts-main > .item > .info > .body > :is(.mail, .phone, .address) > ul > li > a {
	text-decoration: none;
	text-decoration-color: white;
	color: black;

	transition: all 0.2s;
}

.contacts-main > .item > .info > .body > :is(.mail, .phone, .address) > ul > li > a:hover {
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

@media screen and (width <= 1330px) {
	.contacts-main {
		width: 100%;
	}
}

@media screen and (width <= 970px) {
	.contacts-main > .item {
		grid-template-columns: repeat(1, 1fr);
		gap: 20px;
		font-size: 18px;
	}

	.contacts-main > .item > .info > .body {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
	}

	.contacts-main > .item.one > .info > .body {
		grid-template-columns: repeat(2, 1fr);
	}

	.contacts-main > .item > .map > .wall > svg {
		min-width: 600;
		min-height: 500px;
	}
}

@media screen and (width <= 820px) {
	.contacts-main > .item > .info > .body,
	.contacts-main > .item.one > .info > .body {
		grid-template-columns: repeat(1, 1fr);
	}

	.contacts-main > .item > .map {
		width: auto;
		min-width: 100px;
	}
}
</style>
