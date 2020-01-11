import WpApi from "@/services/WpApi";

export const postMeta = { post_id: "post id", meta_id: "meta id" };
export const userMeta = { user_id: "user id", meta_id: "meta id" };
export const getUser = {
  id: "id",
  slug: "slug",
  email: "email",
  login: "login"
};
export const getPost = { id: "id", title: "title", slug: "slug" };
export const createUser = {
  user_pass: "pasword",
  user_login: "login",
  user_nicename: "nicename",
  user_url: "url",
  user_email: "email",
  display_name: "display name",
  nickname: "nickname",
  first_name: "first name",
  last_name: "fast name",
  description: "description",
  rich_editing: "rich editing",
  user_registered: "user registered",
  role: "role",
  jabber: "jabber",
  aim: "aim",
  yim: "yim",
  locale: "locale"
};

export const createPost = {
  post_author: "author",
  post_name: "name",
  post_type: "type",
  post_title: "title",
  post_date: "date",
  post_date_gmt: "date gmt",
  post_content: "content",
  post_excerpt: "excerpt",
  post_status: "status",
  comment_status: "comment status",
  ping_status: "ping status",
  post_password: "post password",
  post_parent: "post parent",
  post_modified: "last modified",
  post_modified_gmt: "last modified gmt",
  comment_count: "comment count",
  menu_order: "menu order"
};

export const params = {
  getUser,
  getPost,
  createUser,
  createPost
};
