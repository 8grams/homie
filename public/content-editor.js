import Alpine from "https://esm.sh/alpinejs@3.14.8/es2022/alpinejs.mjs";

class ContentEditor {
  constructor() {
    this.entries = [];
    this.wrappers = null;
    this.langs = ["en", "id"];
    this.selectedLang = new URL(location).searchParams.get("lang") ?? "en";
    this.urlHash = "";
  }

  init() {
    Alpine.effect(() => {
      history.replaceState(null, "", "?lang=" + this.selectedLang);
    });
  }

  load() {
    const loadedUrl = this.$refs.iframe.contentWindow.location.href;

    // replace /lang/ with /
    this.urlHash = btoa(loadedUrl.replace("/" + this.selectedLang, ""));

    const trans = [
      ...this.$el.contentDocument.querySelectorAll("[data-trans]"),
    ].reduce((entries, el) => {
      const key = el.dataset.trans;
      const entry = entries.find((entry) => entry.key === key);
      if (entry) {
        entry.els.push(el);
        return entries;
      } else {
        return [
          ...entries,
          {
            type: "trans",
            key: key,
            value: el.innerHTML,
            els: [el],
          },
        ];
      }
    }, []);
    const asset = [
      ...this.$el.contentDocument.querySelectorAll("[data-asset]"),
    ].reduce((entries, el) => {
      const key = el.dataset.asset;
      const entry = entries.find((entry) => entry.key === key);
      if (entry) {
        entry.els.push(el);
        return entries;
      } else {
        return [
          ...entries,
          {
            type: "asset",
            key: key,
            value: el.src,
            els: [el],
          },
        ];
      }
    }, []);
    const links = [
      ...this.$el.contentDocument.querySelectorAll("[data-link]"),
    ].reduce((entries, el) => {
      const key = el.dataset.link;
      const entry = entries.find((entry) => entry.key === key);
      if (entry) {
        entry.els.push(el);
        return entries;
      } else {
        return [
          ...entries,
          {
            type: "link",
            key: key,
            value: el.getAttribute("href"),
            els: [el],
          },
        ];
      }
    }, []);
    this.entries = [...trans, ...links, ...asset];
  }

  focus() {
    if (this.wrappers) {
      this.wrappers.forEach(unwrap);
    }
    this.wrappers = this.entry.els.map((el) => wrap(el, this.entry.key));
    this.wrappers[0].scrollIntoView();
  }

  blur() {
    if (this.wrappers) {
      this.wrappers.forEach(unwrap);
    }
    this.wrappers = null;
  }

  input() {
    this.entry.value = this.$el.value;
    this.wrappers.forEach((wrapper) => {
      wrapper.children[1].children[0].innerHTML = this.$el.value;
    });
  }

  linkInput() {
    this.entry.value = this.$el.value;
    this.wrappers.forEach((wrapper) => {
      wrapper.children[1].children[0].setAttribute("href", this.$el.value);
    });
  }

  fileInput() {
    const url = URL.createObjectURL(this.$el.files[0]);
    this.entry.value = url;
    this.entry.els.forEach((el) => {
      el.src = url;
    });
  }
}

const wrap = (el, key) => {
  const wrapper = document.createElement("div");
  const title = document.createElement("div");
  const body = document.createElement("div");
  wrapper.style = "background: #000; color: #fff; padding: 1px";
  body.style = "background: #fff; color: #000";
  title.append(key);
  wrapper.append(title, body);
  el.replaceWith(wrapper);
  body.append(el);
  return wrapper;
};

const unwrap = (wrapper) => {
  wrapper.replaceWith(wrapper.children[1].children[0]);
};

Alpine.data("contentEditor", () => new ContentEditor());
Alpine.start();
