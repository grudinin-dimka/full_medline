export default {
   state: {
      wiki: {
         show: false,
         title: "",
         component: "",
      },
   },
   mutations: {
      setWikiShow(state, show) {
         state.wiki.show = show;
      },

      openWiki(state, options) {
         document.body.classList.add("wiki-open");

         state.wiki.show = true;

         state.wiki.title = options.title;
         state.wiki.component = options.component;
      },

      closeWiki(state) {
         state.wiki.show = false;

         document.body.classList.remove("wiki-open");
      },
   },
   actions: {
   },
   getters: {
      getWikiShow(state) {
         return state.wiki.show;
      },

      getWikiTitle(state) {
         return state.wiki.title;
      },

      getWikiComponent(state) {
         return state.wiki.component;
      },
   },
}