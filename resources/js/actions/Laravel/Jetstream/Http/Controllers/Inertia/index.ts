import UserProfileController from './UserProfileController'
import OtherBrowserSessionsController from './OtherBrowserSessionsController'
import ProfilePhotoController from './ProfilePhotoController'
const Inertia = {
    UserProfileController: Object.assign(UserProfileController, UserProfileController),
OtherBrowserSessionsController: Object.assign(OtherBrowserSessionsController, OtherBrowserSessionsController),
ProfilePhotoController: Object.assign(ProfilePhotoController, ProfilePhotoController),
}

export default Inertia