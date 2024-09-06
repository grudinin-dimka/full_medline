<template>
	<table>
		<thead>
			<tr>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">ID</div>
						<div 
							class="table-th-filter"
							@click="$emit('useFilter', 'id')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Имя</div>
						<div
							class="table-th-filter"
							@click="$emit('useFilter', 'name')"
						>
							<icon-filter-off :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Специальность</div>
						<div class="table-th-filter">
							<icon-filter-off :width="22" :height="22" />
						</div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Скрыть</div>
						<div class="table-th-filter">
							<icon-filter-off :width="22" :height="22" />
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
				<td>{{ specialist.id }}</td>
				<td>{{ specialist.name }}</td>
				<td>{{ specialist.specialization }}</td>
				<td>
					<div class="table-td-checkbox">
						<input type="checkbox" :id="`checkbox-spec-${specialist.id}`" :value="specialist.hide">
					</div>
				</td>
				<td>
					<table-container-buttons>
						<table-button-default @click="$emit('editSpecialist', specialist)">
							Изменить
						</table-button-default>
						<table-button-remove @click="$emit('removeSpecialist', specialist)">
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

export default {
	components: {
		TableButtonDefault,
		TableButtonRemove,
		TableContainerButtons,
		IconFilterOn,
		IconFilterOff,
	},
	props: {
		specialists: {
			type: Array,
			required: true,
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

th:last-of-type {
	width: 100px;
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
	height: 100%
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
</style>
