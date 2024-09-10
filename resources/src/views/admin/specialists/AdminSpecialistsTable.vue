<template>
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
							v-if="filters.id.status"
							@click="useFilter('id', 'default')"
						>
							<icon-filter-on :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Имя</div>
						<div
							class="table-th-filter"
							v-if="!filters.name.status"
							@click="useFilter('name', 'default')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.name.status"
							@click="useFilter('name', 'default')"
						>
							<icon-filter-on :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Специальность</div>
						<div
							class="table-th-filter"
							v-if="!filters.specialization.status"
							@click="useFilter('specialization', 'default')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.specialization.status"
							@click="useFilter('specialization', 'default')"
						>
							<icon-filter-on :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Скрыть</div>
						<div
							class="table-th-filter"
							v-if="!filters.hide.status"
							@click="useFilter('hide', 'default')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.hide.status"
							@click="useFilter('hide', 'default')"
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
			<tr v-for="specialist in specialists">
				<td :class="{ create: specialist.create, delete: specialist.delete }">
					{{ specialist.id }}
				</td>
				<td :class="{ create: specialist.create, delete: specialist.delete }">
					{{ specialist.name }}
				</td>
				<td :class="{ create: specialist.create, delete: specialist.delete }">
					{{ specialist.specialization }}
				</td>
				<td :class="{ create: specialist.create, delete: specialist.delete }">
					<div class="table-td-checkbox">
						<icon-visible-table
							:width="36"
							:height="36"
							v-if="!specialist.hide"
							@click="$emit('touchHideSpecialist', specialist)"
						></icon-visible-table>
						<icon-hide-table
							:width="36"
							:height="36"
							v-if="specialist.hide"
							@click="$emit('touchHideSpecialist', specialist)"
						></icon-hide-table>
					</div>
				</td>
				<td :class="{ create: specialist.create, delete: specialist.delete }">
					<table-container-buttons>
						<table-button-default
							@click="$emit('touchEditSpecialist', 'edit', specialist)"
							:disabled="specialist.delete"
						>
							Изменить
						</table-button-default>
						<table-button-default
							v-if="specialist.delete"
							@click="$emit('touchRemoveSpecialist', specialist)"
						>
							Восстановить
						</table-button-default>
						<table-button-remove
							v-if="!specialist.delete"
							@click="$emit('touchRemoveSpecialist', specialist)"
							:disabled="specialist.create"
						>
							Удалить
						</table-button-remove>
					</table-container-buttons>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
import TableButtonDefault from "../../../components/ui/admin/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/TableButtonRemove.vue";
import TableContainerButtons from "../../../components/ui/admin/TableContainerButtons.vue";

import IconFilterOn from "../../../components/icons/IconFilterOn.vue";
import IconFilterOff from "../../../components/icons/IconFilterOff.vue";

import IconVisible from "../../../components/icons/IconVisible.vue";
import IconHide from "../../../components/icons/IconHide.vue";

import IconVisibleTable from "../../../components/icons/IconVisibleTable.vue";
import IconHideTable from "../../../components/icons/IconHideTable.vue";

export default {
	components: {
		TableButtonDefault,
		TableButtonRemove,
		TableContainerButtons,
		IconFilterOn,
		IconFilterOff,
		IconVisibleTable,
		IconHideTable,
	},
	props: {
		specialists: {
			type: Array,
			required: true,
		},
	},
	data() {
		return {
			filters: {
				id: {
					status: false,
					type: "default",
				},
				name: {
					status: false,
					type: "default",
				},
				specialization: {
					status: false,
					type: "default",
				},
				hide: {
					status: false,
					type: "default",
				},
			},
		};
	},
	methods: {
		useFilter(column, type) {
			this.filters[column].status = true;

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

	animation: show 0.5s ease-out;
}

th,
td {
	padding: 10px;
}

th:nth-of-type(1) {
	width: 50px;
	min-width: 50px;
}

th:nth-of-type(2) {
	min-width: 300px;
}

th:nth-of-type(3) {
	min-width: 150px;
}

th:nth-of-type(4) {
	min-width: 100px;
}

th:nth-of-type(5) {
	min-width: 150px;
}

th {
	position: relative;
	font-weight: 400;
	text-align: left;
	border: 1px solid #1f9fae;
	background-color: #3fbecd;
	color: white;
}

td {
	text-align: left;
	border: 1px solid #1f9fae;
	background-color: #d2f2f5;
}

td.create {
	text-align: left;
	border: 1px solid #3cae1f;
	background-color: #d7f5d2;
}

td.delete {
	text-align: left;
	border: 1px solid #ae1f1f;
	background-color: #f5d2d2;
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

input[type="checkbox"] {
	accent-color: var(--primary-color);
	width: 20px;
	height: 20px;
}

input[type="checkbox"]:checked + label {
	text-decoration: line-through;
}

@keyframes show {
	from {
		opacity: 0.5;
	}
	to {
		opacity: 1;
	}
}

@media screen and (max-width: 1350px) {
	table {
		display: block;
		overflow-x: scroll;
	}
}
</style>
