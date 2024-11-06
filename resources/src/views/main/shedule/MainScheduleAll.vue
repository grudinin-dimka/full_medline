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
					<th>24 ноября</th>
					<th>25 ноября</th>
					<th>26 ноября</th>
					<th>27 ноября</th>
					<th>28 ноября</th>
					<th>29 ноября</th>
					<th>30 ноября</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
					<td>Ячейка</td>
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
table.schedule {
	width: 1700px;
   border: 1px solid black;
}
</style>
