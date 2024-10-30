<template>
	<div class="contacts">
		<div class="item" v-for="contact in contacts" :class="{ one: contact.clinicId == null }">
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
			<div class="map" v-if="contact.clinicId != null">
				<iframe
					style="border-radius: 10px"
					src="https://yandex.ru/map-widget/v1/?um=constructor%3A7af7f250778eef37a11601b4f0f7ff863c6091a436469b719e799d859bf99d33&amp;source=constructor"
					height="100%"
					width="100%"
					frameborder="0"
				></iframe>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		contacts: {
			type: Array,
			required: true,
			default: [],
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
	background-color: rgb(240, 240, 240);
	border-radius: 10px;
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
