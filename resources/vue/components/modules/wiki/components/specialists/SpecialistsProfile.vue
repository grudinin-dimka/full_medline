<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="add" width="14" height="14" fill="var(--primary-color)" />
		Добавление специалиста.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. При добавлении специалиста, недоступны следующие блоки: Специализации, Клиники,
		Сертификаты, Образования, Места работы.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для того, чтобы добавить специалиста, вам необходимо нажать но кнопку «Добавить», которая
		находится справа сверху блока профиля.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» на полях ввдоа появляются сообщения об
		ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить специалиста, нажав на кнопку
		«Добавить» сверху справа блока профиля.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="add" width="14" height="14" fill="var(--primary-color)" />
		Добавление специализаций
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления специализаций к значениям, которые уже определены при их выборе в блоке
		«Специализации», необходимо перейти в раздел «Специализации» на боковой панели администратора.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После добавления специализаций они появятся в блоке «Специализации» на странице с карточкой
		специалиста.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="edit" width="18" height="18" fill="var(--primary-color)" />
		Изменение специалиста
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для изменения данных о специалисте вы можете отредактировать необходимые поля.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для сохранения изменений, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

import General from "../General.vue";

import VueIcon from "../../../icon/VueIcon.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph, General, VueIcon },
};
</script>

<style scoped></style>
