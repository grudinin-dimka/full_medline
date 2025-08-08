<template>
	<div class="modal-list" :class="type">
		<div class="item">
			<div class="content default">
				<slot name="title"></slot>
			</div>
			<div class="buttons">
				<div class="icon create" @click="$emit('create', item)">
					<Icon
						:name="'Add'"
						:fill="'var(--icon-create-fill)'"
						:width="'24px'"
						:height="'24px'"
					/>
				</div>
			</div>
		</div>
		<div class="item" v-for="item in list" :key="item[keys.value]">
			<div class="content">{{ item[keys.label] }}</div>
			<div class="buttons">
				<div class="icon edit" @click="$emit('edit', item)">
					<Icon
						:name="'Edit'"
						:fill="'var(--icon-edit-fill)'"
						:width="'24px'"
						:height="'24px'"
					/>
				</div>
				<div class="icon delete" @click="$emit('delete', item)">
					<Icon
						:name="'Delete'"
						:fill="'var(--delete-secondary-color)'"
						:width="'24px'"
						:height="'24px'"
					/>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Icon from "../icon/VueIcon.vue";

export default {
	emits: ["create", "edit", "delete"],
	components: {
		Icon,
	},
	props: {
		type: {
			type: String,
			default: "",
		},
		keys: {
	 		type: Object,
			default: {
				value: "value",
				label: "label",
			},
		},
		list: {
			type: Array,
			required: true,
			default: [],
		},
	},
};
</script>

<style scoped>
.modal-list {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.modal-list > .item:first-of-type {
	display: flex;
	justify-content: space-between;
	align-items: center;

	border: 0px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px 10px 10px 0px;

	color: var(--primary-color);
}

.modal-list > .item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: var(--input-border);
	border-radius: var(--input-border-radius);
	padding: 10px;
}

.modal-list > .item > .content {
	display: flex;
	align-items: center;
	gap: 10px;


	font-size: 1.125rem;
	word-break: break-all;
}

.modal-list > .item > .content.default {
	font-size: 18px;
	font-family: "Rubik";
}

.modal-list.create > .item > .content.default {
	color: #44a533;
}

.modal-list.delete > .item > .content.default {
	color: #a53333;
}

.modal-list > .item > .buttons {
	display: flex;
	gap: 10px;
}

.modal-list > .item > .buttons > .icon {
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal-list > .item > .buttons > .icon.edit {
	background-color: var(--icon-edit-background-color);
}

.modal-list > .item > .buttons > .icon.edit:hover {
	background-color: var(--icon-edit-background-color-hover);
}

.modal-list > .item > .buttons > .icon.create {
	background-color: var(--icon-create-background-color);
}

.modal-list > .item > .buttons > .icon.create:hover {
	background-color: var(--icon-create-background-color-hover);
}

.modal-list > .item > .buttons > .icon.delete {
	background-color: var(--icon-delete-background-color);
}

.modal-list > .item > .buttons > .icon.delete:hover {
	background-color: var(--icon-delete-background-color-hover);
}
</style>
