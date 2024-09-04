<template>
	<table>
		<thead>
			<tr>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">ID</div>
						<div class="table-th-filter">
                     <icon-filter-off :width="22" :height="22"/>
                  </div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Имя</div>
						<div class="table-th-filter">
                     <icon-filter-off :width="22" :height="22"/>
                  </div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Специальность</div>
						<div class="table-th-filter">
                     <icon-filter-off :width="22" :height="22"/>
                  </div>
					</div>
				</th>
				<th>
					<div class="table-th-container">
						<div class="table-th-name">Действия</div>
						<div class="table-th-filter">
                     <icon-filter-off :width="22" :height="22"/>
                  </div>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="doctor in doctors">
				<td>{{ doctor.id }}</td>
				<td>{{ doctor.name }}</td>
				<td>{{ doctor.specialization }}</td>
				<td>
					<table-container-buttons>
						<table-button-default> Изменить </table-button-default>
						<table-button-remove> Удалить </table-button-remove>
					</table-container-buttons>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
import TableButtonDefault from "./TableButtonDefault.vue";
import TableButtonRemove from "./TableButtonRemove.vue";
import TableContainerButtons from "./TableContainerButtons.vue";

import IconFilterOn from "../../icons/IconFilterOn.vue";
import IconFilterOff from "../../icons/IconFilterOff.vue";

export default {
	components: {
		TableButtonDefault,
		TableButtonRemove,
		TableContainerButtons,
      IconFilterOn, 
      IconFilterOff
	},
	props: {
		doctors: {
			type: Array,
			required: true,
		},
	},
};
</script>

<style scoped>
table {
	border-collapse: collapse;

   animation: show 1s linear;
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

@keyframes show {
	from {
		opacity: 0.5;
	}
	to {
		opacity: 1;
	}
}
</style>
