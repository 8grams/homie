import Alpine from "https://esm.sh/alpinejs@3.14.8";

class ContentEditor {
  constructor() {
    this.entries = [];
    this.wrappers = null;
    this.lines = [];
  }

  load() {
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
    this.entries = [...trans, ...asset];
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

  fileInput() {
    const url = URL.createObjectURL(this.$el.files[0]);
    this.entry.value = url;
    this.entry.els.forEach((el) => {
      el.src = url;
    });
  }

  save() {
    this.lines = JSON.stringify(
      this.entries.map(({ type, key, value }) => {
        return { type, key, value };
      }),
      null,
      2
    )
      .split(/\n/)
      .map((text, index) => {
        return {
          number: index + 1,
          text,
        };
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
