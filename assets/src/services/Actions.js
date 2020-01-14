import {
  postMeta,
  userMeta,
  getUser,
  getPost,
  createUser,
  createPost
} from "@/services/Params";
import { WpApi } from "@/services/WpApi";

export const acfFields = async () => {
  return { cool: "cool" };
  // const acfFields = await WpApi.acfFields;
  // return acfFields;
};

export const actions = [
  {
    id: "create_post",
    displayName: "create post",
    setParams: createPost
  },
  {
    id: "update_post",
    displayName: "update post",
    getParams: getPost,
    setParams: createPost
  },
  {
    id: "get_post",
    displayName: "get post",
    getParams: getPost
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
    setParams: createUser
  },
  {
    id: "get_user",
    displayName: "get user",
    getParams: getUser
  },
  {
    id: "taxonomy",
    displayName: "add categories, tags, etc",
    getParams: getPost
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
    id: "add_acf_post",
    displayName: "add acf data to post",
    getParams: getPost,
    setParams: { null: "null" }
  },
  {
    id: "add_acf_user",
    type: "acf",
    displayName: "add acf data to user",
    getParams: getUser
  }
];
