<template>
	<VueWikiTitle>1. Добавление слайда</VueWikiTitle>
	<VueWikiParagraph>
		1.1. Для того, чтобы добавить слайдер, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока слайдера.
	</VueWikiParagraph>
	<VueWikiParagraph>
		1.2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете
		добавить новый слайдер.
	</VueWikiParagraph>
	<VueWikiParagraph>
		1.3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется
		сообщение об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются
		красным цветом.
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph },
};
</script>

<style scoped></style>
