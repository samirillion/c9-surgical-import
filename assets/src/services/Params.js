import WpApi from "@/services/WpApi";

export const postMeta = ["post_id", "meta_id"];
export const userMeta = ["user_id", "meta_id"];
export const getUser = ["id", "slug", "email", "login"];
export const getPost = ["id", "title", "slug"];
export const createUser = [
  "user_pass",
  "user_login",
  "user_nicename",
  "user_url",
  "user_email",
  "display_name",
  "nickname",
  "first_name",
  "last_name",
  "description",
  "rich_editing",
  "user_registered",
  "role",
  "jabber",
  "aim",
  "yim",
  "locale"
];

export const createPost = {
  post_author: "Author",
  post_name: "Name",
  post_type: "Type",
  post_title: "Title",
  post_date: "Date",
  post_date_gmt: "Date GMT",
  post_content: "Content",
  post_excerpt: "Excerpt",
  post_status: "Status",
  comment_status: "Comment Status",
  ping_status: "Ping Status",
  post_password: "Post Password",
  post_parent: "Post Parent",
  post_modified: "Last Modified",
  post_modified_gmt: "Last Modified GMT",
  comment_count: "Comment Count",
  menu_order: "Menu Order"
};

export const params = {
  getUser,
  getPost,
  createUser,
  createPost
};
