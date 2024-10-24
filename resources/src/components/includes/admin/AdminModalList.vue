<!-- STOP ДОДЕЛАТЬ -->

<template>
	<div class="modal-list">
		<div class="item">
			<div class="content">
				<slot name="title"></slot>
			</div>
			<div class="buttons">
				<div class="icon create" @click="$emit('touchCreate', item)">
					<IconCreate :width="24" :height="24" :type="'create'"></IconCreate>
				</div>
			</div>
		</div>
		<div class="item" v-for="item in array" :key="item.id">
			<div class="content">{{ item.name }}</div>
			<div class="buttons">
				<div class="icon edit" @click="$emit('touchEdit', item)">
					<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
				</div>
				<div class="icon delete" @click="$emit('touchDelete', item)">
					<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import IconCreate from "../../icons/IconCreate.vue";
import IconEdit from "../../icons/IconEdit.vue";
import IconRemove from "../../icons/IconRemove.vue";

export default {
	components: {
		IconCreate,
		IconEdit,
		IconRemove,
	},
	props: {
		array: {
			type: Array,
			required: true,
			default: [],
		},
	},
	data() {},
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

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
}

.modal-list > .item > .content {
	font-size: 18px;
}

.modal-list > .item > .buttons {
	display: flex;
	gap: 10px;
}

.modal-list > .item > .buttons > .icon {
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal-list > .item > .buttons > .icon.edit {
	background-color: rgb(234, 253, 255);
}

.modal-list > .item > .buttons > .icon.edit:hover {
	background-color: rgb(224, 243, 245);
}

.modal-list > .item > .buttons > .icon.create {
	background-color: rgb(234, 255, 236);
}

.modal-list > .item > .buttons > .icon.create:hover {
	background-color: rgb(224, 245, 226);
}

.modal-list > .item > .buttons > .icon.delete {
	background-color: rgb(255, 237, 237);
}

.modal-list > .item > .buttons > .icon.delete:hover {
	background-color: rgb(245, 227, 227);
}
</style>
