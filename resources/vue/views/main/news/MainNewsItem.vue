<template>
	<a
		:href="`/news/${item.url_date}/${item.url_time}`"
		class="news__item"
		:class="{ skeleton: !item.path }"
		@click.prevent="openNews(item)"
	>
		<div class="news__item-date">{{ item.date }}</div>
		<img class="news__item-image" :src="`${item.path}`" v-if="item.path" />
		<div class="news__item-title" v-html="item.title"></div>
	</a>
</template>

<script>
export default {
	props: {
		item: {
			type: Object,
			required: true,
		},
	},
	methods: {
		openNews(item) {
			if (!item.path) {
				return;
			}

			this.$router.push({
				name: "news-once",
				params: { date: item.url_date, time: item.url_time },
			});
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

	border: var(--default-border);
	border-radius: var(--default-border-radius);
	padding: var(--default-padding);

	text-decoration: none;
	color: black;
	height: auto;
	min-height: 450px;
	background-color: var(--skeleton-background-color);

	transition: all 0.2s ease-out;
}

.news__item:hover {
	background-image: linear-gradient(
		120deg,
		var(--skeleton-background-color) 50%,
		var(--skeleton-line-color) 60%,
		var(--skeleton-line-color) 61%,
		var(--skeleton-background-color) 70%
	);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

.news__item-date {
	font-size: 1.125rem;
	color: rgba(0, 0, 0, 0.5);
}

.news__item-image {
	width: 100%;
	aspect-ratio: 1/1;
	object-fit: cover;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);

	animation: show 0.5s ease-in-out;
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
