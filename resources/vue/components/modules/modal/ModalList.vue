<template>
	<div class="modal-list" :class="type">
		<div class="item">
			<div class="content default">
				<slot name="title"></slot>
			</div>
			<div class="buttons">
				<div class="icon create" @click="$emit('touchCreate', item)">
					<Icon
						:name="'add'"
						:fill="'var(--icon-create-fill)'"
						:width="'19px'"
						:height="'19px'"
					/>
				</div>
			</div>
		</div>
		<div class="item" v-for="item in array" :key="item.id">
			<div class="content">{{ item.name }}</div>
			<div class="buttons">
				<div class="icon edit" @click="$emit('touchEdit', item)">
					<Icon
						:name="'edit'"
						:fill="'var(--icon-edit-fill)'"
						:width="'24px'"
						:height="'24px'"
					/>
				</div>
				<div class="icon delete" @click="$emit('touchDelete', item)">
					<Icon
						:name="'delete'"
						:fill="'var(--icon-delete-fill)'"
						:width="'20px'"
						:height="'20px'"
					/>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Icon from "../icon/Icon.vue";

export default {
	components: {
		Icon,
	},
	props: {
		type: {
			type: String,
			default: "",
		},
		array: {
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
