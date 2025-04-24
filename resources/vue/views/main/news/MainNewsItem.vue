<template>
	<div
		class="news__item"
		@click="
			$router.push({ name: 'news-once', params: { date: item.url_date, time: item.url_time } })
		"
	>
		<div class="news__item-date">{{ item.date }}</div>
		<img class="news__item-image" :src="`${item.path}`" />
		<div class="news__item-title" v-html="item.title"></div>
	</div>
</template>

<script>
export default {
	props: {
		item: {
			type: Object,
			required: true,
		},
	},
};
</script>

<style scoped>
.news__item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: 20px;
	border: var(--input-border);
	border-radius: var(--default-border-radius);
	height: auto;
	min-height: 400px;

	transition: all 0.2s;
}

.news__item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.news__item-date {
	font-size: 1.125rem;
	color: rgba(0, 0, 0, 0.5);
}

.news__item-image {
	width: 100%;
	max-height: 300px;
	object-fit: cover;
	border-radius: calc(var(--default-border-radius) / 2);
}

.news__item-title {
	font-size: 1.25rem;
}

.news__item-title > :is(h1, h2, h3, h4, h5, h6) {
	font-size: 1.25rem;
   font-weight: normal;
}

:is(.news__item-title, .news__item-content) > * {
	margin: 0px;
}

.news__item-content {
	height: 100px;
	overflow-y: hidden;

	font-size: 1.125rem;
}
</style>
