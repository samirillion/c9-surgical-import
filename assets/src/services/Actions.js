import {
  postMeta,
  userMeta,
  getUser,
  getPost,
  createUser,
  createPost,
  updateUser,
  addFeatured
} from "@/services/Params";
import { WpApi } from "@/services/WpApi";

// all action names have accompanying functions in class-wp-importer
let baseActions = [
  {
    id: "create_post",
    displayName: "create post",
    setParams: createPost
  },
  {
    id: "add_featured_image",
    displayName: "add featured image",
    getParams: getPost,
    setParams: addFeatured
  },
  {
    id: "create_user",
    displayName: "create user",
    setParams: createUser
  },
  {
    id: "update_user",
    displayName: "update user",
    getParams: getUser,
    setParams: updateUser
  },
  {
    id: "update_post",
    displayName: "update post",
    getParams: getPost,
    setParams: createPost
  },
  {
    id: "get_post",
    displayName: "get post (of import loop)",
    getParams: getPost
  },
  {
    id: "get_user",
    displayName: "get user (of import loop)",
    getParams: getUser
  },
  {
    id: "add_post_terms",
    displayName: "add categories, tags, etc., to post",
    getParams: getPost,
    setParams: { custom: "taxonomies" }
  },
  {
    id: "add_post_meta",
    displayName: "add post meta",
    getParams: getPost,
    setParams: { null: "null" }
  },
  {
    id: "add_user_meta",
    displayName: "add user meta",
    getParams: getUser,
    setParams: { null: "null" }
  },
  {
    id: "add_acf",
    displayName: "add acf data",
    getParams: getPost,
    setParams: { custom: "acfFields" }
  }
];

// Add Custom Params
export const getActions = async () => {
  let params = await WpApi.importParams();
  let hydratedActions = baseActions.map(action => {
    let container = action;
    if (
      undefined !== action.setParams &&
      undefined !== action.setParams.custom
    ) {
      container.setParams = params[action.setParams.custom];
    }
    return container;
  });
  return hydratedActions;
};
