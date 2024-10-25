<template>
	<div class="contacts">
		<div class="item" @click="$emit('touchEditContact', contact)" v-for="contact in contacts">
			<div class="head">
				<div>id: {{ contact.id }}</div>
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
								{{ phone.name }}
							</li>
							<li v-else>
								<span class="empty"> Отсутствует </span>
							</li>
						</ul>
					</div>
					<div class="mail">
						<div>Почта:</div>
						<ul>
							<li v-if="contact.mails.length > 0" v-for="mail in contact.mails">
								{{ mail.name }}
							</li>
							<li v-else>
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
export default {
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
	grid-auto-rows: 250px;
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

.contacts > .item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.contacts > .item:hover > .head > div {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
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
}

.contacts > .item > .body > .info {
	display: grid;
	grid-template-columns: repeat(2, minmax(200px, 1fr));
	gap: 10px;
}

.contacts > .item > .body > .info > :is(.mail, .phone) > ul {
	padding: 0px;
	margin: 10px 10px;
	inline-size: 80%;
}

.contacts > .item > .body > .info > :is(.mail, .phone) > ul > li {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;

	margin-top: 10px;
}

.contacts > .item > .body > .info > .mail > ul > li::before {
	content: "🖂";
	padding-right: 10px;
	font-weight: bold;
}

.contacts > .item > .body > .info > .phone > ul > li::before {
	content: "☎";
	padding-right: 10px;
	font-weight: bold;
}

.contacts > .item > .body > .phone > ul {
	padding: 0px 30px;
	margin: 5px;
}

.contacts > .item > .body > .phone > ul > li {
	padding: 10px;
	margin: 0px;
}

span.empty {
	color: #c7c7c7;
}
</style>
