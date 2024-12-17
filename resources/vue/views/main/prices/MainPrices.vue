<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<block :minHeight="400">
		<div class="prices">
			<div class="filter-blocks">
				<div class="item">
					<div class="clear-filter" v-if="false">Очистить</div>
					<container-input-once :type="'default'">
						<template #title>
							<span>АДРЕС КЛИНИКИ</span>
						</template>
						<template #input>
							<select>
								<option value="" selected>Ничего не выбрано</option>
								<option value="1">Делаю что-то</option>
							</select>
						</template>
					</container-input-once>
				</div>
				<div class="item">
					<div class="clear-filter" v-if="false">Очистить</div>
					<container-input-once :type="'default'">
						<template #title>
							<span>КАТЕГОРИЯ</span>
						</template>
						<template #input>
							<select>
								<option value="" selected>Ничего не выбрано</option>
							</select>
						</template>
					</container-input-once>
				</div>
			</div>

			<div class="prices-item">
				<div class="title">Компьютерная томография (КТ)</div>
				<ol>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ головы</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ мягких тканей</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ сосудов</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ шеи</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ брюшной полости и малого таза</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ грудной клетки</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ позвоночника</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">КТ суставов</div>
						<div class="price">3000 руб.</div>
					</li>
				</ol>
			</div>
			<div class="prices-item">
				<div class="title">Магнитно-резонансная томография (МРТ)</div>
				<ol>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
					<li>
						<div class="name">Виртуальная эндоскопия</div>
						<div class="price">3000 руб.</div>
					</li>
				</ol>
			</div>
		</div>

		<!-- <loader-child :isLoading="loading.loader.about" /> -->
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		ContainerInputOnce,
	},
	data() {
		return {
			loading: {
				loader: {
					about: true,
				},
				sections: {
					about: false,
				},
			},
		};
	},
};
</script>

<style scoped>
/* Блоки фильтров */
.filter-blocks {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;

	width: 1350px;
	animation: show 0.5s ease-out;
}

.filter-blocks > .item {
	position: relative;
	flex: 1 0 350px;

	border-radius: 10px;
	padding: 15px;

	border: 2px solid var(--input-border-color-inactive);
	background-color: rgba(235, 235, 235, 0);

	transition: all 0.2s;
}

.filter-blocks > .item.active {
	border: 2px solid #44a533;
}

.filter-button.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.clear-filter {
	position: absolute;
	cursor: pointer;

	top: 12px;
	right: 20px;
	color: var(--button-remove-color);
}

.clear-filter:hover {
	text-decoration: underline;
}

.clear-filter > .point {
	position: absolute;
	top: 2px;

	border-radius: 30px;

	width: 16px;
	height: 16px;
}

.clear-filter > .point.default {
	left: 2px;
	background-color: #b6b6b6;
}

.clear-filter > .point.active {
	right: 2px;
	background-color: var(--primary-color);
}

.adresses {
	display: flex;
	overflow-x: auto;

	width: 1350px;
}

.prices {
	width: 1350px;
	display: grid;
	grid-template-columns: repeat(1, 1fr);
	gap: 20px;
}

.prices-item > .title {
	font-size: 1.25rem;
	font-weight: 600;
	color: #00abbd;
}

.prices-item > ol {
	list-style: none;
	display: grid;
	grid-template-columns: repeat(2, 1fr);

	padding: 0px;
	margin: 0px;

	column-gap: 40px;
}

.prices-item > ol > li {
	display: flex;
	justify-content: space-between;

	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: #d2d2d2;

	padding: 10px;

	font-size: 1.125rem;
}
</style>
