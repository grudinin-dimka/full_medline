<template>
	<table>
		<thead>
			<tr>
				<!-- <th>
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
							v-if="filters.id.status && filters.id.type === 'reverse'"
							@click="useFilter('id', 'default')"
						>
							<icon-filter-on :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.id.status && filters.id.type === 'default'"
							@click="useFilter('id', 'reverse')"
						>
							<icon-filter-on-reverse :width="22" :height="22" />
						</div>
					</div>
				</th> -->
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
							v-if="
								filters.specialization.status && filters.specialization.type === 'default'
							"
							@click="useFilter('specialization', 'reverse')"
						>
							<icon-filter-on-reverse :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="
								filters.specialization.status && filters.specialization.type === 'reverse'
							"
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
							v-if="filters.hide.status && filters.hide.type === 'default'"
							@click="useFilter('hide', 'reverse')"
						>
							<icon-filter-on-reverse :width="22" :height="22" />
						</div>
						<div
							class="table-th-filter"
							v-if="filters.hide.status && filters.hide.type === 'reverse'"
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
			<tr v-if="specialists.length == 0">
				<td colspan="5" class="table-td-empty">Ничего нету...</td>
			</tr>
			<tr
				v-else
				v-for="specialist in specialists"
				:class="{ create: specialist.create, delete: specialist.delete }"
			>
				<!-- <td>
					{{ specialist.id }}
				</td> -->
				<td>
					{{ specialist.name }}
				</td>
				<td>
					{{ specialist.specializations }}
					<span class="table-td-empty" v-if="specialist.specializations.length == 0"
						>Ничего не выбрано...</span
					>
				</td>
				<td>
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
				<td>
					<table-container-buttons>
						<table-button-default
							@click="
								$router.push({ name: 'especialist-profile', params: { id: specialist.id } })
							"
							v-if="!specialist.delete"
						>
							Открыть
						</table-button-default>
						<TableButtonDisabled v-if="specialist.delete"> Открыть </TableButtonDisabled>

						<table-button-remove
							@click="$emit('touchRemoveSpecialist', specialist.id)"
							v-if="!specialist.delete"
						>
							Удалить
						</table-button-remove>
						<table-button-default
							@click="$emit('touchRemoveSpecialist', specialist.id)"
							v-if="specialist.delete"
						>
							Вернуть
						</table-button-default>
					</table-container-buttons>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
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
		specialists: {
			type: Array,
			required: true,
		},
	},
	data() {
		return {
			filters: {
				// id: {
				// 	status: false,
				// 	type: "default",
				// },
				name: {
					status: true,
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
			this.filters[column].type = type;

			for (let key in this.filters) {
				if (key !== column) {
					this.filters[key].status = false;
				}
			}

			this.$emit("touchUseFilter", column, type);
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

tr > td {
	text-align: left;
	border: 1px solid #3fbecd;
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

tr.delete:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

th:nth-of-type(0) {
	width: 50px;
	min-width: 50px;
}

th:nth-of-type(1) {
	min-width: 300px;
	width: 40%;
}

th:nth-of-type(2) {
	min-width: 300px;
	width: 30%;
}

th:nth-of-type(3) {
	min-width: 70px;
}

th:last-child {
	width: 300px;
	min-width: 100px;
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
	color: rgba(0, 0, 0, 0.3);
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
	0% {
		opacity: 0;
	}
	100% {
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
