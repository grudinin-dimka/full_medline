<template>
	<div class="contacts">
		<div class="item" v-for="contact in contacts" :class="{ one: contact.clinic.id == null }">
			<div class="info">
				<div class="title">{{ contact.name }}</div>
				<div class="body">
					<div class="phone">
						<div class="title">Телефоны:</div>
						<ul>
							<li v-for="phone in contact.phones" v-if="contact.phones.length > 0">
								<a :href="`tel:${phone.name}}`" :key="phone.id">{{ phone.name }}</a>
							</li>
							<li v-else>
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="mail">
						<div class="title">Почта:</div>
						<ul>
							<li v-for="mail in contact.mails" v-if="contact.mails.length > 0">
								<a :href="`mailto:${mail.name}`">{{ mail.name }}</a>
							</li>
							<li v-else>
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div
				class="map"
				v-if="contact.clinic.id != null"
				@click="showMap(contact.id)"
				:id="`map-${contact.id}`"
			>
				<!-- <div class="title" v-if="false">Загрузить карту</div> -->
				<div
					class="body"
					:id="`map-container-${contact.id}`"
					v-if="true"
					style="width: 100%; height: 100%; border-radius: 10px"
				></div>
			</div>
		</div>
	</div>
</template>

<script>
async function initMap(mapId) {
	ymaps3.ready.then(() => {
		let { YMap, YMapDefaultSchemeLayer, YMapMarker, YMapDefaultFeaturesLayer } = ymaps3;

		let map = new YMap(document.querySelector("#map-container-" + mapId), {
			location: {
				center: [37.588144, 55.733842],
				zoom: 16,
			},
		});

		map.addChild(new YMapDefaultSchemeLayer());

		// STOP делал метки
		const content = document.createElement("div");
		content.innerHTML = "<p>Draggable paragraph</p>";
		map.addChild(new YMapDefaultFeaturesLayer({ zIndex: 1800 }));
		map.addChild(
			new YMapMarker(
				{
					coordinates: [37.588144, 55.733842],
					draggable: true,
				},
				content
			)
		);
	});
}

export default {
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
		showMap(mapId) {
			let element = document.querySelector("#map-" + mapId);
			element.style.cursor = "default";

			initMap(mapId);
		},
	},
};
</script>

<style scoped>
.contacts {
	width: 1250px;
	display: flex;
	flex-direction: column;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.contacts > .item {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 20px;
	font-size: 18px;
}

.contacts > .item.one {
	grid-template-columns: 1fr;
}

.contacts > .item > .info {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts > .item > .info > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts > .item.one > .info > .body {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 20px;
}

.contacts > .item > .info > .title {
	font-size: 22px;
	font-weight: bold;
	color: var(--primary-color);
}

.contacts > .item > .map {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	height: 300px;

	border: 2px solid var(--input-border-color-inactive);

	color: var(--input-border-color-inactive);

	transition: all 0.2s;
}

.contacts > .item > .map:hover {
	border: 2px solid var(--input-border-color-active);
	color: var(--input-border-color-active);
}

.contacts > .item > .map > .title {
	display: flex;
	justify-content: center;
	align-items: center;
}

:is(.mail, .phone) > ul {
	padding: 0px;
	margin: 0px 0px 0px 20px;
	inline-size: 80%;

	overflow-y: auto;
}

:is(.mail, .phone) > ul > li {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;

	margin-top: 10px;
}

:is(.mail, .phone) > ul > li > a {
	text-decoration: none;
	text-decoration-color: white;
	color: black;

	transition: all 0.2s;
}

:is(.mail, .phone) > ul > li > a:hover {
	text-decoration: underline;
	text-decoration-thickness: 1.5px;
	text-decoration-color: var(--primary-color);
}

.mail > ul > li::before {
	content: "🖂";
	padding-right: 10px;
	font-weight: bold;
}

.phone > ul > li::before {
	content: "☎";
	padding-right: 10px;
	font-weight: bold;
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
</style>
