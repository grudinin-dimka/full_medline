<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
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
		3. В случае, когда при нажатии на кнопку «Добавить» на полях ввода появляются сообщения об
		ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. Для фото специаплиста поддерживаются следующие форматы:
		<span :style="{ color: 'var(--primary-color)' }">.png</span>,
		<span :style="{ color: 'var(--primary-color)' }">.webp</span>. Рекомендуется загружать файлы
		формата <span :style="{ color: 'var(--primary-color)' }">.webp</span>, так как они имеют
		высокое качество и небольшой размер.
	</VueWikiParagraph>
	<VueWikiParagraph>
		5. После соблюдения предыдущих пунктов, вы сможете добавить специалиста, нажав на кнопку
		«Добавить» сверху справа блока профиля.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление специализаций
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления специализаций к значениям, которые уже определены при их выборе в блоке
		«Специализации», необходимо перейти в раздел «Специализации» на боковой панели администратора
		в блоке «Специалисты».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После добавления специализаций они появятся в блоке «Специализации» на странице с карточкой
		специалиста.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление клиник
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления клиник к значениям, которые уже определены при их выборе в блоке «Клиники»,
		необходимо перейти в раздел «Клиники» на боковой панели администратора в блоке «Специалисты».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После добавления специализаций они появятся в блоке «Клиники» на странице с карточкой
		специалиста.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
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

export default {
	components: { VueWikiTitle, VueWikiParagraph, General },
};
</script>

<style scoped></style>
