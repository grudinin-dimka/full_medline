<template>
	<!-- HACK Добавить transition group -->
	<table>
		<thead>
			<tr>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">ID</div>
						<div
							class="table-th-filter"
							v-if="!filters.id.status"
							@click="useFilter('id', 'default')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.id.status && filters.id.type === 'default'"
							@click="useFilter('id', 'reverse')"
						>
							<icon-filter-on-reverse :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.id.status && filters.id.type === 'reverse'"
							@click="useFilter('id', 'default')"
						>
							<icon-filter-on :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Название</div>
						<div
							class="table-th-filter"
							v-if="!filters.name.status"
							@click="useFilter('name', 'default')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.name.status && filters.name.type === 'default'"
							@click="useFilter('name', 'reverse')"
						>
							<icon-filter-on-reverse :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.name.status && filters.name.type === 'reverse'"
							@click="useFilter('name', 'default')"
						>
							<icon-filter-on :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Действия</div>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr v-if="array.length == 0">
				<td colspan="5" class="table-td-empty">Ничего нету...</td>
			</tr>
			<tr v-else v-for="value in array" :class="{ create: value.create, delete: value.delete }">
				<td v-if="!value.create">
					{{ value.id }}
				</td>
				<td v-else>?</td>
				<td>
					{{ value.name }}
				</td>
				<td>
					<table-container-buttons>
						<table-button-default
							@click="$emit('touchEditArrValue', value)"
							v-if="!value.delete"
						>
							Открыть
						</table-button-default>
						<TableButtonDisabled v-else> Открыть </TableButtonDisabled>
						<table-button-remove
							v-if="!value.delete && !value.create"
							@click="$emit('touchRemoveArrValue', value.id)"
						>
							Удалить
						</table-button-remove>
						<TableButtonDisabled v-if="value.create"> Удалить </TableButtonDisabled>
						<table-button-default
							v-if="value.delete"
							@click="$emit('touchRemoveArrValue', value.id)"
						>
							Восстановить
						</table-button-default>
					</table-container-buttons>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
import TableButtonClaim from "../../../components/ui/admin/tables/TableButtonClaim.vue";
import TableButtonDefault from "../../../components/ui/admin/tables/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/tables/TableButtonRemove.vue";
import TableButtonDisabled from "../../../components/ui/admin/tables/TableButtonDisabled.vue";
import TableContainerButtons from "../../../components/ui/admin/tables/TableContainerButtons.vue";

import IconFilterOn from "../../../components/icons/IconFilterOn.vue";
import IconFilterOnReverse from "../../../components/icons/IconFilterOnReverse.vue";
import IconFilterOff from "../../../components/icons/IconFilterOff.vue";

import IconVisibleTable from "../../../components/icons/IconVisibleTable.vue";
import IconHideTable from "../../../components/icons/IconHideTable.vue";

import { RouterLink } from "vue-router";

export default {
	components: {
		TableButtonClaim,
		TableButtonDefault,
		TableButtonRemove,
		TableButtonDisabled,
		TableContainerButtons,
		IconFilterOn,
		IconFilterOnReverse,
		IconFilterOff,
		IconVisibleTable,
		IconHideTable,
		RouterLink,
	},
	props: {
		array: {
			type: Array,
			required: true,
		},
	},
	data() {
		return {
			filters: {
				id: {
					status: true,
					type: "default",
				},
				name: {
					status: false,
					type: "default",
				},
			},
		};
	},
	methods: {
		useFilter(column, type) {
			this.filters[column].status = true;
			this.filters[column].type = type;

			for (let key in this.filters) {
				if (key !== column) {
					this.filters[key].status = false;
				}
			}

			this.$emit("useFilter", column, type);
		},
	},
};
</script>

<style scoped>
table {
	border-collapse: collapse;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

th,
td {
	padding: 10px;
	transition: all 0.2s;
	height: 30px;
}

tr {
	background-color: #d2f2f5;
	transition: all 0.2s;
}

tr.create > td {
	text-align: left;
	border: 1px solid #3cae1f;
	background-color: #d7f5d2;
}

tr.delete > td {
	text-align: left;
	border: 1px solid #ae1f1f;
	background-color: #f5d2d2;
}

tr > th {
	position: relative;
	font-weight: 400;
	text-align: left;
	border: 1px solid #2d9aa7;
	background-color: #3fbecd;
	color: white;
}

tr > th:first-of-type {
	width: 50px;
	min-width: 50px;
}

tr > th:nth-of-type(2) {
	width: 100%;
	min-width: 500px;
}

tr > th:last-of-type {
	width: 300px;
	min-width: 300px;
}

tr > td {
	text-align: left;
	border: 1px solid #3fbecd;
}

tr > td:first-of-type {
	text-align: center;
}

tr:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

tr.delete {
	background-color: #f5d2d2;
}

tr.delete:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

tr.create {
	background-color: #d7f5d2;
}

tr.create:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

.table-th-container {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.table-th-filter {
	cursor: pointer;
	position: absolute;
	right: 5px;
}

.table-td-checkbox {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
}

td.table-td-empty {
	text-align: center;
	color: #90cad1;
}

span.table-td-empty {
	color: #90cad1;
}

input[type="checkbox"] {
	accent-color: var(--primary-color);
	width: 20px;
	height: 20px;
}

input[type="checkbox"]:checked + label {
	text-decoration: line-through;
}

@media screen and (max-width: 1300px) {
	table {
		display: block;
		overflow-x: scroll;
	}
}
</style>
