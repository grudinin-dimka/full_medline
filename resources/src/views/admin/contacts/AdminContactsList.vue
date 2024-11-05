<template>
	<div class="contacts">
		<div
			class="item"
			@click="$emit('touchEditContact', contact)"
			v-for="contact in contacts"
			:class="{ create: contact.create, delete: contact.delete }"
		>
			<div class="head">
				<div>id: {{ contact.create ? "?" : contact.id }}</div>
				<div>order: {{ contact.order }}</div>
			</div>
			<div class="body">
				<div class="title">{{ contact.name }}</div>
				<div>
					Клиника:
					<span :class="{ empty: contact.clinicId == 'null' || contact.clinicId == null }">
						{{ clinics.find((item) => item.id == contact.clinicId)?.name ?? "Отсутствует" }}
					</span>
				</div>
				<div class="info">
					<div class="phone">
						<div>Номера:</div>
						<ul>
							<li v-if="contact.phones.length > 0" v-for="phone in contact.phones">
								<IconContactPhone :width="14" :height="14" />
								{{ phone.name }}
							</li>
							<li v-else>
								<IconContactPhone :width="14" :height="14" />
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="mail">
						<div>Почта:</div>
						<ul>
							<li v-if="contact.mails.length > 0" v-for="mail in contact.mails">
								<IconContactMail :width="16" :height="14" />
								{{ mail.name }}
							</li>
							<li v-else>
								<IconContactMail :width="16" :height="14" />
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import IconContactHome from "../../../components/icons/contacts/IconContactHome.vue";
import IconContactMail from "../../../components/icons/contacts/IconContactMail.vue";
import IconContactPhone from "../../../components/icons/contacts/IconContactPhone.vue";

export default {
	components: {
		IconContactHome,
		IconContactMail,
		IconContactPhone,
	},
	props: {
		contacts: {
			type: Array,
			required: true,
			default: [],
		},
		clinics: {
			type: Array,
			required: true,
			default: [],
		},
	},
};
</script>

<style scoped>
.contacts {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	grid-auto-rows: 300px;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.contacts > .item {
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
}

.contacts > .item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;

	font-size: 16px;

	transition: all 0.2s;
}

.contacts > .item:hover > .head > div {
	border: 2px solid var(--input-border-color-active);
}

.contacts > .item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.contacts > .item.create {
	border: 2px solid var(--create-border-color);
}

.contacts > .item.create:hover {
	background-color: var(--create-background-color);
	border: 2px solid var(--create-border-color);
}

.contacts > .item.create > .head > div {
	border: 2px solid var(--create-border-color);
}

.contacts > .item.delete {
	border: 2px solid #ec7b7b;
}

.contacts > .item.delete:hover {
	border: 2px solid #f79999;
	background-color: rgb(255, 240, 240);
}

.contacts > .item.delete > .head > div {
	border: 2px solid #ec7b7b;
}

.contacts > .item > .head {
	display: flex;
	gap: 10px;
}

.contacts > .item > .head > div {
	padding: 5px 10px;
	border-radius: 7.5px;
	border: 2px solid var(--input-border-color-inactive);

	transition: all 0.2s;
}

.contacts > .item > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts > .item > .body > .title {
	font-size: 22px;
	color: var(--primary-color);

	transition: all 0.2s;
}

.contacts > .item.create > .body > .title {
	color: #44a533;
}

.contacts > .item.delete > .body > .title {
	color: #a53333;
}

.contacts > .item > .body > .info {
	display: grid;
	grid-template-columns: repeat(2, minmax(200px, 1fr));
	gap: 10px;
}

.contacts > .item > .body > .info > :is(.mail, .phone) > ul {
	padding: 0px;
	margin: 0px 0px 0px 20px;
	inline-size: 80%;

	overflow-y: auto;
	height: 145px;
}

/* STOP делал цвета скролла, надо дальше сделать по идее добавление нового контакта */
.contacts > .item:hover > .body > .info > :is(.mail, .phone) > ul::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #f2feff;
	border-radius: 10px;
}

.contacts > .item.delete:hover > .body > .info > :is(.mail, .phone) > ul::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 240, 240);
	border-radius: 10px;
}

.contacts > .item.create:hover > .body > .info > :is(.mail, .phone) > ul::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid var(--create-background-color);
	border-radius: 10px;
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

.contacts > .item > .body > .info > :is(.mail, .phone) > ul > li {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;

	margin-top: 10px;
}

span.empty {
	color: #c7c7c7;
}

@media screen and (width <= 1810px) {
	.contacts {
		grid-template-columns: repeat(2, 1fr);
		grid-auto-rows: none;
	}
}

@media screen and (max-width: 1360px) {
	.contacts {
		grid-template-columns: repeat(1, 1fr);
	}
}

@media screen and (max-width: 530px) {
	.contacts > .item > .body > .info {
		grid-template-columns: minmax(200px, 1fr);
	}

	.contacts {
		grid-auto-rows: 400px;
	}

	.contacts > .item > .body > .info > :is(.mail, .phone) > ul {
		height: 100px;
	}
}
</style>
