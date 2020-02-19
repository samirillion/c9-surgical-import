export const postMeta = { post_id: "post id", meta_id: "meta id" };
export const userMeta = { user_id: "user id", meta_id: "meta id" };
export const getUser = {
  ID: "user id"
  // user_email: "user email",
  // user_login: "user login"
};

export const addFeatured = {
  url: "image url (required)",
  default_url: "default image url",
  img_name: "image name"
};
export const getPost = {
  ID: "post id"
  // post_title: "post title"
};
export const updateUser = {
  user_pass: "user password",
  user_login: "username",
  user_nicename: "user nicename",
  user_url: "user url",
  user_email: "user email",
  display_name: "user display name",
  nickname: "user nickname",
  first_name: "user first name",
  last_name: "user last name",
  description: "user description",
  rich_editing: "rich editing",
  user_registered: "user registered",
  role: "role",
  jabber: "jabber",
  aim: "aim",
  yim: "yim",
  locale: "locale"
};
export const createUser = {
  user_pass: "user password (required)",
  user_login: "username (required)",
  user_nicename: "user nicename",
  user_url: "user url",
  user_email: "user email (recommended)",
  display_name: "user display name",
  nickname: "user nickname",
  first_name: "user first name",
  last_name: "user last name",
  description: "user description",
  rich_editing: "rich editing",
  user_registered: "user registered",
  role: "role",
  jabber: "jabber",
  aim: "aim",
  yim: "yim",
  locale: "locale"
};

export const createPost = {
  post_author: "post author",
  post_name: "post name",
  post_type: "post type",
  post_title: "post title (required)",
  post_date: "post date",
  post_date_gmt: "post date gmt",
  post_content: "post content",
  post_excerpt: "post excerpt",
  post_status: "post status",
  comment_status: "post comment status",
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
