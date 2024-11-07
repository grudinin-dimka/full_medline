<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>
	<block :minHeight="400">
		<filters :filters="filters" :minWidth="1700"></filters>
		<table class="schedule">
			<thead>
				<tr>
					<th>Ф.И.О.</th>
					<th>Специализация</th>
					<th width="100px">24 ноября Понедельник</th>
					<th width="100px">25 ноября Вторник</th>
					<th width="100px">26 ноября Среда</th>
					<th width="100px">27 ноября Четверг</th>
					<th width="100px">28 ноября Пятница</th>
					<th width="100px">29 ноября Суббота</th>
					<th width="100px">30 ноября Воскресенье</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Иванов Иван Иванович</td>
					<td>Хирург</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
				</tr>
				<tr>
					<td>Иванов Иван Иванович</td>
					<td>Хирург</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
				</tr>
				<tr>
					<td>Иванов Иван Иванович</td>
					<td>Хирург</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
				</tr>
				<tr>
					<td>Иванов Иван Иванович</td>
					<td>Хирург</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
					<td>08:00-08:30</td>
				</tr>
			</tbody>
		</table>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import Filters from "../../../components/ui/main/Filters.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		Filters,
	},
	data() {
		return {
			filters: [
				{
					id: 1,
					name: "ул. Пушкина, д. Колотушкина",
				},
				{
					id: 2,
					name: "ул. Ваньки, д. Жопосранки",
				},
			],
		};
	},
};
</script>

<style scoped>
table {
	border-collapse: collapse;
	animation: show-bottom-to-top-15 0.5s ease-in-out;

	width: 100%;
	max-width: 1700px;
	border: 1px solid black;

	overflow: scroll;
}

th {
	white-space: pre-wrap;
	word-wrap: break-word;
	text-align: center;
}

td {
	text-align: left;
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
	border: 1px solid #3cae1f;
	background-color: #d7f5d2;
}

tr.delete > td {
	border: 1px solid #ae1f1f;
	background-color: #f5d2d2;
}

tr > th {
	position: relative;
	font-weight: 400;
	border: 1px solid #3fbecd;
	background-color: #3fbecd;
	color: white;
}

tr > td {
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

tr.create {
	background-color: #d7f5d2;
}

tr.create:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}
</style>
